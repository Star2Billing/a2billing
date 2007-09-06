#!/usr/bin/perl -wall

use strict;
use DBI;
use POSIX qw(setsid);
use vars qw($dbh);
use Config::IniFiles;

$|++;


######################### READ THE CONFIG FILE ########################
my $conf_file = "/etc/asterisk/a2billing.conf";
# Read the config file
my $cfg = Config::IniFiles->new( -file => $conf_file );

if (not defined $cfg) { 
       print "Failed to parse $conf_file: \n"; 
       foreach(@Config::IniFiles::errors) { 
               print "Error: $_\n" ; 
       } 
       exit(1); 
} 
######################### DB PARAMETER ########################
my $dbname = $cfg->val('database', 'dbname');
my $dbhost = $cfg->val('database', 'hostname');
my $dbport = $cfg->val('database', 'port');
my $login = $cfg->val('database', 'user');
my $pwd = $cfg->val('database', 'password');
my $dbtype = $cfg->val('database', 'dbtype');


my ($dbh, $sth, $sth2, @row, $cid, $sid);
my $SQL="";
my $QUERY="";




if ($dbtype eq "mysql")
{
        $dbh ||= DBI->connect("dbi:mysql:$dbname:$dbhost", "$login", "$pwd");
}else{
        $dbh ||= DBI->connect("dbi:Pg:dbname=$dbname;host=$dbhost;port=$dbport", "$login", "$pwd");
}

if (!$dbh) {
        die "ERR: Couldn't open connection: ".$DBI::errstr."\n";
}



#########################
## Fixup #1
##  compared to the v1.2.3 schema a field id_cc_card has been added to the SIP/IAX friends tables pointing at the parent: cc_card.id
##  We need to populate this field by looking up on the username in cc_card

$SQL = "SELECT cc_sip_buddies.id,c.id FROM cc_sip_buddies INNER JOIN cc_card AS c ON cc_sip_buddies.username = c.username;";
$sth = $dbh->prepare($SQL);
$sth->execute();
while ( @row = $sth->fetchrow ) {
        $sid = $row[0]; $cid = $row[1];
        $QUERY = "UPDATE cc_sip_buddies SET id_cc_card = '$cid' WHERE id='$sid'";
        $sth2 = $dbh->prepare($QUERY);
        $sth2->execute();
        $sth2->finish();
}
$sth->finish();

$SQL = "SELECT cc_iax_buddies.id,c.id FROM cc_iax_buddies INNER JOIN cc_card AS c ON cc_iax_buddies.username = c.username;";
$sth = $dbh->prepare($SQL);
$sth->execute();
while ( @row = $sth->fetchrow ) {
        $sid = $row[0]; $cid = $row[1];
        $QUERY = "UPDATE cc_iax_buddies SET id_cc_card = '$cid' WHERE id='$sid'";
        $sth2 = $dbh->prepare($QUERY);
        $sth2->execute();
        $sth2->finish();
}
$sth->finish();

$dbh->finish();
exit 0

