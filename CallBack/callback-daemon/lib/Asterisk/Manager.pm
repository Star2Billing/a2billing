package Asterisk::Manager;

require 5.004;

use Asterisk;
use IO::Socket;
use Digest::MD5;

use strict;
use warnings;

=head1 NAME

Asterisk::Manager - Asterisk Manager Interface

=head1 SYNOPSIS

use Asterisk::Manager;

my $astman = new Asterisk::Manager;

$astman->user('username');

$astman->secret('test');

$astman->host('localhost');

$astman->connect || die "Could not connect to " . $astman->host . "!\n";

$astman->disconnect;

=head1 DESCRIPTION

This module provides a simple interface to the asterisk manager interface.

=cut

my $EOL = "\r\n";
my $BLANK = $EOL x 2;

my $VERSION = '0.01';

sub version { $VERSION; }

sub new {
	my ($class, %args) = @_;

	my $self = {};
	$self->{_CONNFD} = undef;
	$self->{_PROTOVERS} = undef;
	$self->{_ERRORSTR} = undef;
	$self->{_HOST} = 'localhost';
	$self->{_PORT} = 5038;
	$self->{_USER} = undef;
	$self->{_SECRET} = undef;
	$self->{_EVENTCB} = {};
	$self->{_DEBUG} = 0;
	$self->{_CONNECTED} = 0;
	bless $self, ref $class || $class;
	return $self;
}

sub DESTROY { }

sub user {
	my ($self, $user) = @_;

	if ($user) {
		$self->{_USER} = $user;
	}

	return $self->{_USER};
}

sub secret {
	my ($self, $secret) = @_;

	if ($secret) {
		$self->{_SECRET} = $secret;
	}

	return $self->{_SECRET};
}

sub host {
	my ($self, $host) = @_;

	if ($host) {
		$self->{_HOST} = $host;
	}

	return $self->{_HOST};
}

sub port {
	my ($self, $port) = @_;

	if ($port) {
		$self->{_PORT} = $port;
	}

	return $self->{_PORT};
}

sub connected {
	my ($self, $connected) = @_;

	if (defined($connected)) {
		$self->{_CONNECTED} = $connected;
	}

	return $self->{_CONNECTED};
}

sub error {
	my ($self, $error) = @_;

	if ($error) {
		$self->{_ERRORSTR} = $error;
	}

	return $self->{_ERRORSTR};
}

sub debug {
	my ($self, $debug) = @_;

	if ($debug) {
		$self->{_DEBUG} = $debug;
	}

	return $self->{_DEBUG};
}

sub connfd {
	my ($self, $connfd) = @_;

	if ($connfd) {
		$self->{_CONNFD} = $connfd;
	}

	return $self->{_CONNFD};
}

sub read_response {
	my ($self, $connfd) = @_;

	my @response;

	if (!$connfd) {
		$connfd = $self->connfd;
	}

	while (my $line = <$connfd>) {
		last if ($line eq $EOL);

		if (wantarray) {
			$line =~ s/$EOL//g;
			push(@response, $line) if $line;
		} else {
			$response[0] .= $line;
		}

	}

	return wantarray ? @response : $response[0];
}

sub connect {
	my ($self) = @_;

	my $host = $self->host;
	my $port = $self->port;
	my $user = $self->user;
	my $secret = $self->secret;
	my %resp;

	my $conn = new IO::Socket::INET( Proto => 'tcp',
					 PeerAddr => $host,
					 PeerPort => $port
					);
	if (!$conn) {
		$self->error("Connection refused ($host:$port)\n");
		return undef;
	}

	$conn->autoflush(1);

	my $input = <$conn>;
	$input =~ s/$EOL//g;

	my ($manager, $version) = split('/', $input);

	if ($manager !~ /Asterisk Call Manager/) {
		return $self->error("Unknown Protocol\n");
	}

	$self->{_PROTOVERS} = $version;
	$self->connfd($conn);

	# check if the remote host supports MD5 Challenge authentication
	my %authresp = $self->sendcommand( Action => 'Challenge',
					   AuthType => 'MD5'
					 );

	if ((defined($authresp{Response}) && $authresp{Response} eq 'Success')) {
		# do md5 login
		my $md5 = new Digest::MD5;
		$md5->add($authresp{Challenge});
		$md5->add($secret);
		my $digest = $md5->hexdigest;
		%resp = $self->sendcommand(  Action => 'Login',
					     AuthType => 'MD5',
					     Username => $user,
					     Key => $digest
					  );
	} else {
		# do plain text login
		%resp = $self->sendcommand(  Action => 'Login',
					     Username => $user,
					     Secret => $secret
					  );

	}

	if ( ($resp{Response} ne 'Success') && ($resp{Message} ne 'Authentication accepted') ) {
		$self->error("Authentication failed for user $user\n");
		return undef;
	}

	$self->connected(1);

	return $conn;
}

sub astman_h2s {
	my ($self, %thash) = @_;

	my $tstring = '';

	foreach my $key (keys %thash) {
		$tstring .= $key . ': ' . $thash{$key} . ${EOL};
	}

	return $tstring;
}

sub astman_s2h {
	my ($self, $tstring) = @_;

	my %thash;

	foreach my $line (split(/$EOL/, $tstring)) {
		if ($line =~ /(\w*):\s*(\w*)/) {
			$thash{$1} = $2;
		}
	}

	return %thash;
}

#$want is how you want the data returned
#$want = 0 (default) returns the results in a hash
#$want = 1 returns the results in a large string
#$want = 2 returns the results in an array
sub sendcommand {
	my ($self, %command, $want) = @_;

	if (!defined($want)) {
		$want = 0;
	}
	
	my $conn = $self->connfd || return;
	my $cstring = $self->astman_h2s(%command);

	
	$conn->send("$cstring$EOL");
	
	if ($want == 1) {
		my $response = $self->read_response($conn);
		print "\n=====>response:$response$EOL";
		return $response;
	}

	my @resp = $self->read_response($conn);

	if ($want == 2) {
		return @resp;
	} else {
		return map { split(': ', $_) } @resp;
	}
}

sub setcallback {
	my ($self, $event, $function) = @_;

	if (defined($function) && ref($function) eq 'CODE') {
		$self->{_EVENTCB}{$event} = $function;
	}
}

sub eventcallback {
	my ($self, %resp) = @_;

	my $callback;
	my $event = $resp{Event};

	return if (!$event);

	if (defined($self->{_EVENTCB}{$event})) {
		$callback = $self->{_EVENTCB}{$event};
	} elsif (defined($self->{_EVENTCB}{DEFAULT})) {
		$callback = $self->{_EVENTCB}{DEFAULT};
	} else {
		return;
	}

	return &{$callback}(%resp);
}

sub eventloop {
	my ($self) = @_;

	while (1) {
		$self->handleevent;
	}
}

sub handleevent {
	my ($self) = @_;

	my %resp = map { split(': ', $_); } $self->read_response;
	$self->eventcallback(%resp);

	return %resp;
}

sub action {
	my ($self, $command, $wanthash) = @_;

	return if (!$command);

	my $conn = $self->connfd || return;

	print $conn "Action: $command" . $BLANK;
	my @resp = $self->read_response($conn);

	if ($wanthash) {
		return map { split(': ', $_) } @resp;
	} elsif (wantarray) {
		return @resp;
	} else {
		return $resp[0];
	}
}

sub command {
	my ($self, $command) = @_;

	return if (!$command);

	return $self->sendcommand('Action' => 'Command',
				  'Command' => $command, 1 );
}

sub disconnect {
	my ($self) = @_;

	my $conn = $self->connfd;

	my %resp = $self->sendcommand('Action' => 'Logoff');
	print "\n===============";
	print %resp;
	print "===============\n";
	if (defined($resp{Response}) && $resp{Response} eq 'Goodbye') {
		$self->{_CONNFD} = undef;
		$self->connected(0);
		return 1;
	}

	return 0;
}

1;
