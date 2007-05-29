package Asterisk::AGI;

require 5.004;

use Asterisk;

@ISA = ( 'Asterisk' );

=head1 NAME

Asterisk::AGI - Simple Asterisk Gateway Interface Class

=head1 SYNOPSIS

use Asterisk::AGI;

$AGI = new Asterisk::AGI;

# pull AGI variables into %input

	%input = $AGI->ReadParse();   

# say the number 1984

	$AGI->say_number(1984);

=head1 DESCRIPTION

This module should make it easier to write scripts that interact with the
asterisk open source pbx via AGI (asterisk gateway interface)

=head1 AGI COMMANDS

=cut

sub new {
	my ($class, %args) = @_;
	my $self = {};
	$self->{'callback'} = undef;
	$self->{'status'} = undef;
	$self->{'lastresponse'} = undef;
	bless $self, ref $class || $class;
	return $self;
}

sub ReadParse {
	my ($self, $fh) = @_;

	my %input = ();

	$fh = \*STDIN if (!$fh);

	while (<$fh>) {
		chomp;
		last unless length($_);
		if (/^agi_(\w+)\:\s+(.*)$/) {
			$input{$1} = $2;
		}
	}
	

	if (defined($DEBUG)&&($DEBUG>0)) {
		print STDERR "AGI Environment Dump:\n";
		foreach $i (sort keys %input) {
			print STDERR " -- $i = $input{$i}\n";
		}
	}

	return %input;
}

sub setcallback {
	my ($self, $function) = @_;

	if (defined($function) && ref($function) eq 'CODE') {
		$self->{'callback'} = $function;
	} 
}

sub callback {
	my ($self, $result) = @_;

	if (defined($self->{'callback'}) && ref($self->{'callback'}) eq 'CODE') {
		&{$self->{'callback'}}($result);
	}
}

sub execute {
	my ($self, $command) = @_;

	$self->_execcommand($command);
	my $res = $self->_readresponse();

	return $self->_checkresult($res);
}

sub _execcommand {
	my ($self, $command, $fh) = @_;

	$fh = \*STDOUT if (!$fh);

	select ((select ($fh), $| = 1)[0]);

	return -1 if (!defined($command));

	return print $fh "$command\n";
}

sub _readresponse {
	my ($self, $fh) = @_;

	my $response = undef;
	$fh = \*STDIN if (!$fh);
	$response = <$fh> || return '200 result=-1 (noresponse)';
	chomp($response);
	return $response;
}

sub _checkresult {
	my ($self, $response) = @_;

	return undef if (!defined($response));
	my $result = undef;

	$self->_lastresponse($response);
	if ($response =~ /^200/) {
		if ($response =~ /result=(-?[\d*#]+)/) {
			$result = $1;
		}
	} elsif ($response =~ /\(noresponse\)/) {
		$self->_status('noresponse');
	} else {
		print STDERR "Unexpected result '$response'\n" if (defined($DEBUG) && $DEBUG);
	}
	print STDERR "_checkresult($response) = $result\n" if (defined($DEBUG) && $DEBUG>3);

	return $result;				
}

sub _status {
	my ($self, $status) = @_;

	if (defined($status)) {
		$self->{'status'} = $status;
	} else {
		return $self->{'status'};
	}
}

sub _lastresponse {
	my ($self, $response) = @_;

	if (defined($response)) {
		$self->{'lastresponse'} = $response;
	} else {
		return $self->{'lastresponse'};
	}
}

=over 4

=item $AGI->stream_file($filename, $digits)

Executes AGI Command "STREAM FILE $filename $digits"

This command instructs Asterisk to play the given sound file and listen for the given dtmf digits. The
fileextension must not be used in the filename because Asterisk will find the most appropriate file
type.

Example: $AGI->stream_file('demo-echotest', '0123');

Returns: -1 on error or hangup,
0 if playback completes without a digit being pressed,
or the ASCII numerical value of the digit if a digit was pressed

=cut

sub stream_file {
	my ($self, $filename, $digits) = @_;

	my $ret = 0;

	$digits = '""' if (!defined($digits));

	return -1 if (!defined($filename));
	$ret =  $self->execute("STREAM FILE $filename $digits");

	$self->callback($ret) if ($ret == -1);

	return $ret;
}

=item $AGI->send_text($text)

Executes AGI Command "SEND TEXT "$text"

Sends the given text on a channel.  Most channels do not support the transmission of text.

Example: $AGI->send_text('You've got mail!');

Returns: -1 on error or hangup,
0 if the text was sent or if the channel does not support text transmission.

=cut

sub send_text {
	my ($self, $text) = @_;

	my $ret = 0;

	return $ret if (!defined($text));
	$ret = $self->execute("SEND TEXT \"$text\"");
	$self->callback($ret) if ($ret == -1);

	return $ret;
}

=item $AGI->send_image($image)

Executes AGI Command "SEND IMAGE $image

Sends the given image on a channel.  Most channels do not support the transmission of images.

Example: $AGI->send_image('image.png');

Returns: -1 on error or hangup,
0 if the image was sent or if the channel does not support image transmission.

=cut

sub send_image {
	my ($self, $image) = @_;

	my $ret = 0;
	return -1 if (!defined($image));

	$ret = $self->execute("SEND IMAGE $image");
	$self->callback($ret) if ($ret == -1);

	return $ret;
}

=item $AGI->say_number($number, $digits)

Executes AGI Command "SAY NUMBER $number $digits"

Says the given $number, returning early if any of the $digits are received.

Example: $AGI->say_number('98765');

Returns: -1 on error or hangup,
0 if playback completes without a digit being pressed, 
or the ASCII numerical value of the digit of one was pressed.

=cut

sub say_number {
	my ($self, $number, $digits) = @_;

	my $ret = 0;

	$digits = '""' if (!defined($digits));

	return -1 if (!defined($number));
	$number =~ s/\D//g;
	$ret = $self->execute("SAY NUMBER $number $digits");

	$self->callback($ret) if ($ret == -1);

	return $ret;
}

=item $AGI->say_digits($number, $digits)

Executes AGI Command "SAY DIGITS $number $digits"

Says the given digit string $number, returning early if any of the $digits are received.

Example: $AGI->say_digits('8675309');

Returns: -1 on error or hangup,
0 if playback completes without a digit being pressed, 
or the ASCII numerical value of the digit of one was pressed.

=cut

sub say_digits {
        my ($self, $number, $digits) = @_;

	my $ret = 0;
	$digits = '""' if (!defined($digits));

	return -1 if (!defined($number));
	$number =~ s/\D//g;
	$ret = $self->execute("SAY DIGITS $number $digits");
	$self->callback($ret) if ($ret == -1);

	return $ret;
}

=item $AGI->answer()

Executes AGI Command "ANSWER"

Answers channel if not already in answer state

Example: $AGI->answer();

Returns: -1 on channel failure, or
0 if successful

=cut

sub answer {
	my ($self) = @_;

	my $ret = 0;
	$ret = $self->execute('ANSWER');
	$self->callback($ret) if ($ret == -1);

	return $ret;

}

=item $AGI->get_data($filename, $timeout, $maxdigits)

Executes AGI Command "GET DATA $filename $timeout $maxdigits"

Streams $filename and returns when $maxdigits have been received or
when $timeout has been reached.  Timeout is specified in ms

Example: $AGI->get_data('demo-welcome', 15000, 5);

=cut

sub get_data {
	my ($self, $filename, $timeout, $maxdigits) = @_;

	my $ret = undef;

	return -1 if (!defined($filename));
	$ret = $self->execute("GET DATA $filename $timeout $maxdigits");
	$self->callback($ret) if ($ret == -1);

	return $ret;
}

=item $AGI->set_callerid($number)

Executes AGI Command "SET CALLERID $number"

Changes the callerid of the current channel to <number>

Example: $AGI->set_callerid('9995551212');

Returns: Always returns 1

=cut

sub set_callerid {
	my ($self, $number) = @_;

	return if (!defined($number));
	return $self->execute("SET CALLERID $number");
}

=item $AGI->set_context($context)

Executes AGI Command "SET CONTEXT $context"

Changes the context for continuation upon exiting the agi application

Example: $AGI->set_context('dialout');

Returns: Always returns 0

=cut

sub set_context {
	my ($self, $context) = @_;

	return -1 if (!defined($context));
	return $self->execute("SET CONTEXT $context");
}

=item $AGI->set_extension($extension)

Executes AGI Command "SET EXTENSION $extension"

Changes the extension for continuation upon exiting the agi application

Example: $AGI->set_extension('7');

Returns: Always returns 0

=cut

sub set_extension {
	my ($self, $extension) = @_;

	return -1 if (!defined($extension));
	return $self->execute("SET EXTENSION $extension");
}

=item $AGI->set_priority($priority)

Executes AGI Command "SET PRIORITY $priority"

Changes the priority for continuation upon exiting the agi application

Example: $AGI->set_priority(1);

Returns: Always returns 0

=cut

sub set_priority {
	my ($self, $priority) = @_;

	return -1 if (!defined($priority));
	return $self->execute("SET PRIORITY $priority");
}

sub receive_char {
	my ($self, $timeout) = @_;

	my $ret = 0;
#wait forever if timeout is not set. is this the prefered default?
	$timeout = 0 if (!defined($timeout));
	$ret = $self->execute("RECEIVE CHAR $timeout");
	$self->callback($ret) if ($ret == -1);

	return $ret;

}

sub tdd_mode {
	my ($self, $mode) = @_;

	return 0 if (!defined($mode));
	return $self->execute("TDD MODE $mode");
}


sub wait_for_digit {
	my ($self, $timeout) = @_;

	my $ret = 0;
	$timeout = -1 if (!defined($timeout));
	$ret = $self->execute("WAIT FOR DIGIT $timeout");

	$self->callback($ret) if ($ret == -1);

	return $ret;
}

sub record_file {
	my ($self, $filename, $format, $digits, $timeout, $beep) = @_;

	my $ret = 0;

	return -1 if (!defined($filename));
	$digits = '""' if (!defined($digits));
	$ret = $self->execute("RECORD FILE $filename $format $digits $timeout");

	$self->callback($ret) if ($ret == -1);

	return $ret;
}

sub set_autohangup {
	my ($self, $time) = @_;

	$time = 0 if (!defined($time));
	return $self->execute("SET AUTOHANGUP $time");
}

=item $AGI->hangup($channel)

Executes AGI Command "HANGUP $channel"

Hangs up the passed $channel, or the current channel if $channel is not passed.
It is left to the AGI script to exit properly, otherwise you could end up with zombies.

Example: $AGI->hangup();

Returns: Always returns 1

=cut

sub hangup {
	my ($self, $channel) = @_;

	if ($channel) {
		return $self->execute("HANGUP $channel");
	} else {
		return $self->execute("HANGUP");
	}
}

=item $AGI->exec($app, $options)

Executes AGI Command "EXEC $app $options"

The most powerful AGI command.  Executes the given application passing the given options.

Example: $AGI->exec('Dial', 'Zap/g2/8005551212');

Returns: -2 on failure to find application, or
whatever the given application returns

=cut

sub exec {
	my ($self, $app, $options) = @_;
	return -1 if (!defined($app));
	$options = '""' if (!defined($options));
	return $self->execute("EXEC $app $options");
}

sub channel_status {
	my ($self, $channel) = @_;

	return $self->execute("CHANNEL STATUS $channel");
}

=item $AGI->set_variable($variable, $value)

Executes AGI Command "SET VARIABLE $variable $value"

Sets the channel variable <variablename> to <value>

Example: $AGI->set_variable('status', 'authorized');

Returns: Always returns 1

=cut

sub set_variable {
	my ($self, $variable, $value) = @_;

	return $self->execute("SET VARIABLE $variable $value");
}

=item $AGI->get_variable($variable)

Executes AGI Command "GET VARIABLE $variablename"

Gets the channel variable <variablename>

Example: $AGI->get_variable('status');

Returns: The value of the variable, or undef if variable does not exist

=cut

sub get_variable {
	my ($self, $variable) = @_;

	my $result = undef;

	if ($self->execute("GET VARIABLE $variable")) {
		my $tempresult = $self->_lastresponse();
		if ($tempresult =~ /\((.*)\)/) {
			$result = $1;
		}
	}
	return $result;
}

=item $AGI->verbose($message, $level)

Executes AGI Command "VERBOSE $message $level"

Logs $message with verboselevel $level

Example: $AGI->verbose("System Crashed\n", 1);

Returns: Always returns 1

=cut


sub verbose {
	my ($self, $message, $level) = @_;

	return $self->execute("VERBOSE \"$message\" $level");
}

=item $AGI->database_get($family, $key)

Executes AGI Command "DATABASE GET $family $key"

Example: $var = $AGI->database_get('test', 'status');

Returns: The value of the variable, or undef if variable does not exist

=cut

sub database_get {
	my ($self, $family, $key) = @_;

	my $result = undef;

	if ($self->execute("DATABASE GET $family $key")) {
		my $tempresult = $self->_lastresponse();
		if ($tempresult =~ /\((.*)\)/) {
			$result = $1;
		}
	}
	return $result;
}

=item $AGI->database_put($family, $key, $value)

Executes AGI Command "DATABASE PUT $family $key $value"

Set/modifes database entry <family>/<key> to <value>

Example: $AGI->database_put('test', 'status', 'authorized');

Returns: 1 on success, 0 otherwise

=cut

sub database_put {
	my ($self, $family, $key, $value) = @_;

	return $self->execute("DATABASE PUT $family $key $value");
}

=item $AGI->database_del($family, $key)

Executes AGI Command "DATABASE DEL $family $key"

Removes database entry <family>/<key>

Example: $AGI->database_del('test', 'status');

Returns: 1 on success, 0 otherwise

=cut

sub database_del {
	my ($self, $family, $key) = @_;

	return $self->execute("DATABASE DEL $family $key");
}

=item $AGI->database_deltree($family, $key)

Executes AGI Command "DATABASE DELTREE $family $key"

Deletes a family or specific keytree within a family in the Asterisk database

Example: $AGI->database_deltree('test', 'status'); 
Example: $AGI->database_deltree('test');

Returns: 1 on success, 0 otherwise

=cut

sub database_deltree {
	my ($self, $family, $key) = @_;

	return $self->execute("DATABASE DELTREE $family $key");
}

sub noop {
	my ($self) = @_;

	return $self->execute("NOOP");
}

sub set_music {
	my ($self, $mode, $class) = @_;

	return $self->execute("SET MUSIC $mode $class");
}

1;

__END__

=back 
