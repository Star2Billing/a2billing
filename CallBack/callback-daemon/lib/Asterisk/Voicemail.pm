package Asterisk::Voicemail;

require 5.004;

=head1 NAME

Asterisk::Voicemail - Stuff to deal with asterisk voicemail

=head1 SYNOPSIS

This is not completed yet

=head1 DESCRIPTION

description

=cut

use Asterisk;

@ISA = ( 'Asterisk' );

$VERSION = '0.01';

$DEBUG = 5;

sub version { $VERSION; }

sub new {
	my ($class, %args) = @_;
	my $self = {};
	bless $self, ref $class || $class;
#        while (my ($key,$value) = each %args) { $self->set($key,$value); }
	return $self;
}

sub DESTROY { }

sub spooldirectory {
	my ($self, $directory) = @_;

	if (defined($directory)) {
		$self->{'spooldirectory'} = $directory;
	} else {
		$self->{'spooldirectory'} = '/var/spool/asterisk/vm' if (!defined($self->{'spooldirectory'}));
	}

	return $self->{'spooldirectory'};
}

sub sounddirectory {
	my ($self, $directory) = @_;

	if (defined($directory)) {
		$self->{'sounddirectory'} = $directory;
	} else {
		$self->{'sounddirectory'} = '/var/lib/asterisk/sounds' if (!defined($self->{'sounddirectory'}));
	}

	return $self->{'sounddirectory'};
}

sub serveremail {
	my ($self, $email) = @_;

	$self->{'serveremail'} = $email if (defined($email));

	return $self->{'serveremail'};
}

sub format {
	my ($self, @formats) = @_;

	if (@formats) {
		$self->{'formats'} = @formats;
	}

	return $self->{'formats'};
}

sub vmbox {
	my ($self, $vmbox, $vmpass, $vmfn, $vmemail) = @_;

	if (defined($vmbox) && (defined($vmpass) || defined($vmfn) || defined($vmemail)) ) {
		$self->{'vmbox'}{$vmbox}{'pass'} = $vmpass if (defined($vmpass));
		$self->{'vmbox'}{$vmbox}{'fn'} = $vmfn if (defined($vmfn));
		$self->{'vmbox'}{$vmbox}{'email'} = $vmemail if (defined($vmemail));
	} elsif (defined($vmbox)) {
		return ($self->{'vmbox'}{$vmbox}{'pass'},
			$self->{'vmbox'}{$vmbox}{'fn'},
			$self->{'vmbox'}{$vmbox}{'email'} );
	}

}

sub getfolders {
	my ($self, $vmbox) = @_;

	my @folders = ();

        my $spool = $self->spooldirectory();

	foreach $file (<$spool/$vmbox/*>) {
		if ( -d $file ) {
			$file =~ s/$spool\/$vmbox\///;
			push(@folders, $file);
		}
	}
	return @folders;
}

sub configfile {
	my ($self, $configfile) = @_;

	if (defined($configfile)) {
		$self->{'configfile'} = $configfile;
	} else {
		$self->{'configfile'} = '/etc/asterisk/voicemail.conf' if (!defined($self->{'configfile'}));
	}

	return $self->{'configfile'};
}

sub readconfig {
	my ($self) = @_;

	my $context = '';
	my $line = '';

	my $configfile = $self->configfile();

	open(CF, "<$configfile") || die "Error loading $configfile: $!\n";
	while ($line = <CF>) {
		chop($line);

		$line =~ s/;.*$//;
		$line =~ s/\s*$//;

		if ($line =~ /^;/) {
			next;
		} elsif ($line =~ /^\s*$/) {
			next;
		} elsif ($line =~ /^\[(\w+)\]$/) {
			$context = $1;
			print STDERR "Context: $context\n" if ($DEBUG>3);
		} elsif ($line =~ /^format\s*[=>]+\s*(.*)/) {
			my $formats = $1;
			$self->format(split(/|/, $formats));
		} elsif ($line =~ /^serveremail\s*[=>]+\s*(.*)/) {
			$self->serveremail($1);
		} elsif ($line =~ /^(\d+)\s*[=>]+\s*(.*)/) {
			my $vmbox = $1;
			my $vmstr = $2;
			my ($vmpass, $vmfn, $vmemail) = split(/,/, $vmstr);
			print STDERR "VM BOX ($vmbox)\n" if ($DEBUG>3);
			$self->vmbox($vmbox, $vmpass, $vmfn, $vmstr);
		} else {
			print STDERR "Unknown line: $line\n" if ($DEBUG);
		}
	}

	close(CF);
}

sub appendsoundfile {
	my ($self, $source, $dest) = @_;

	open(IN, "<$source") || return 0;
	open(OUT, ">>$dest") || return 0;
	while (<IN>) {
		print OUT $_;
	}
	close(IN);
	close(OUT);
	return 1;
}

sub validmailbox {
	my ($self, $vmbox) = @_;

	return 1 if ($self->vmbox($vmbox));
	return 0;
}

sub msgcount {
	my ($self, $vmbox, $folder) = @_;

	my $count = 0;

	return $count if (!defined($vmbox) || !defined($folder));

	my $spool = $self->spooldirectory() . '/' . $vmbox . '/' . $folder;

	if (opendir(DIR, $spool)) {
		my @msgs = grep(/^msg.*\.txt$/, readdir(DIR));
		$count = $#msgs+1;
		closedir(DIR);
	}
	return $count;
}

sub msgcountstr {
	my ($self, $vmbox, $folder) = @_;

	my $count = $self->msgcount($vmbox, $folder);

	if ($count > 1) {
		return "$count messages";
	} elsif ($count > 0) {
		return "$count message";
	} else {
		return "no messages";
	}
}

sub createdefaultmailbox {
	my ($self, $vmbox) = @_;

	my $spool = $self->spooldirectory();
	my $sounddir = $self->sounddirectory();

	my $vmdir = "$spool/$vmbox";


	if ( -d $vmdir ) {
		print STDERR "Directory already exists: $vmdir\n" if ($DEBUG);
	} else {
		mkdir($vmdir, 0755) || return 0;
		mkdir("$vmdir/INBOX", 0755) || return 0;
	}

	$self->appendsoundfile("$sounddir/vm-theperson.gsm", "$vmdir/unavail.gsm");
	$self->appendsoundfile("$sounddir/vm-theperson.gsm", "$vmdir/busy.gsm");
	$self->appendsoundfile("$sounddir/vm-extension.gsm", "$vmdir/greet.gsm");

	foreach $chr (split(//, $vmbox)) {
		$self->appendsoundfile("$sounddir/digits/$chr.gsm", "$vmdir/unavail.gsm");
		$self->appendsoundfile("$sounddir/digits/$chr.gsm", "$vmdir/busy.gsm");
		$self->appendsoundfile("$sounddir/digits/$chr.gsm", "$vmdir/greet.gsm");
	}

	$self->appendsoundfile("$sounddir/vm-isunavail.gsm", "$vmdir/unavail.gsm");
	$self->appendsoundfile("$sounddir/vm-isonphone.gsm", "$vmdir/busy.gsm");

	return 1;
}

sub messages {
	my ($self, $messages, $folder) = @_;

        my $path = $self->spooldirectory() . '/' . $mailbox . '/' . $folder;
        if (opendir(DIR, $path)) {
                my @msgs = sort grep(/^msg....\.txt$/, readdir(DIR));
                closedir(DIR);
                return map { s/^msg(....)\.txt$/$1/; $_ } @msgs;
        }
        return ();
}

1;

