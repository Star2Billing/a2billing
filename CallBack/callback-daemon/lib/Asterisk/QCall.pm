package Asterisk::QCall;

require 5.004;

use Fcntl ':flock';
use Asterisk;

$VERSION = '0.01';

sub version { $VERSION; }

sub new {
	my ($class, %args) = @_;
	my $self = {};
	$self->{QUEUEDIR} = '/var/spool/asterisk/qcall';
	$self->{QUEUETIME} = undef;
	bless $self, ref $class || $class;
	return $self;
}

sub DESTROY { }

sub queuedir {
	my ($self, $dir) = @_;

	if (defined($dir)) {
		$self->{QUEUEDIR} = $dir;
	}

	return $self->{QUEUEDIR};
}

sub queuetime {
	my ($self, $time) = @_;

	if (defined($time)) {
		$self->{QUEUETIME} = $time;
	} elsif (!defined($self->{QUEUETIME})) {
                $self->{QUEUETIME} = time();
	}

	return $self->{QUEUETIME};
}

sub create_qcall {
	my ($self, $dialstring, $callerid, $extension, $maxsecs, $identifier, $response) = @_;

	my $time = $self->queuetime();

	my $queuedir = $self->queuedir();
	my $filename = $queuedir . '/' . $time . '.queue';
	open(QFILE, ">$filename") || return 0;
	flock(QFILE, LOCK_EX);
	print QFILE "$dialstring $callerid $extension $maxsecs $identifier $response";
	flock(QFILE, LOCK_UN);
	close(QFILE);
	my $ret = utime($time, $time, $filename);
	return 1;
}

1;
