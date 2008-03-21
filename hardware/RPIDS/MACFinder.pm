package RPIDS::MACFinder;

use strict;
use warnings;

require Exporter;

our @ISA = qw/Exporter/;
our @EXPORT = qw/find_mac/;

sub find_mac {
    my $if = shift;
    my $ifconfig = `/sbin/ifconfig $if`;
    if ($ifconfig =~ /HWaddr (([0-9a-fA-F]{2}:){5}[0-9a-fA-F]{2})/) {
        return $1;
    }
}

