package Asterisk::Outgoing;

=head1 NAME

Asterisk::Outgoing - Create outgoing call queue file

=head1 SYNOPSIS

use Asterisk::Outgoing;

my $out = new Asterisk::Outgoing;

$out->setvariable('Channel', 'Zap/1/');

$out->setvariable('MaxRetries', 0);

$out->setvariable('RetryTime', 60);

$out->setvariable('WaitTime', 60);

$out->setvariable('Application', 'Playback');

$out->setvariable('Data', 'beep');

$out->create_outgoing;

=cut

require 5.004;

use Fcntl ':flock';
use Asterisk;
@ISA = ('Asterisk');

$VERSION = '0.01';

sub new {
	my ($class, %args) = @_;
	my $self = {};
	$self->{OUTDIR} = '/var/spool/asterisk/outgoing';
	$self->{OUTTIME} = undef;
	$self->{OUTVARS} = {};
	$self->{ALLOWEDVARS} = [ 'channel', 'maxretries', 'retrytime', 'waittime', 'context', 'extension', 'priority', 'application', 'data', 'callerid' ]; 
	bless $self, ref $class || $class;
	return $self;
}

sub outdir {
	my ($self, $dir) = @_;

	if (defined($dir)) {
		$self->{OUTDIR} = $dir;
	}

	return $self->{OUTDIR};
}

sub outtime {
	my ($self, $time) = @_;

	if (defined($time)) {
		$self->{OUTTIME} = $time;
	} elsif (!defined($self->{OUTTIME})) {
                $self->{OUTTIME} = time();
	}

	return $self->{OUTTIME};
}

sub checkvariable {
	my ($self, $var) = @_;

	my $ret = 0;

	foreach $allowed (@{$self->{ALLOWEDVARS}}) {
		if ($allowed =~ /$var/i) {
			$ret = 1;
		}
	}
	return $ret;
}

sub setvariable {
	my ($self, $var, $value) = @_;

	if ($self->checkvariable($var)) {
		$self->{OUTVARS}{$var} = $value;
	}
}

sub create_outgoing {
	my ($self) = @_;

	my $time = $self->outtime();

	my $outdir = $self->outdir();
	my $filename = $outdir . '/' . $time . '.outgoing';
	open(OUTFILE, ">$filename") || return 0;
	flock(OUTFILE, LOCK_EX);
	utime($time, $time, $filename);
	foreach my $var (keys %{$self->{OUTVARS}}) {
		print OUTFILE "$var: " . $self->{OUTVARS}{$var} . "\n";
	}
	flock(OUTFILE, LOCK_UN);
	close(OUTFILE);
	utime($time, $time, $filename);
	return 1;
}

1;
