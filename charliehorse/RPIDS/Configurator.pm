package RPIDS::Configurator;

use strict;
use warnings;

require Exporter;

use RPIDS::MACFinder;
use LWP::Simple( ); # don't import anything
use Crypt::OpenSSL::RSA; # for signature verification
use MIME::Base64;

our @ISA = qw/Exporter/;
our @EXPORT = qw/configure/;
our $BASE_URL = "http://ds.rpitv.org/charliehorse/";

sub get_summary {
    my $mac = shift;
    return LWP::Simple::get("$BASE_URL/config_summary.php?mac=$mac");    
}

sub load_key {
    local $/ = undef;
    open my $keyfile, "<", "/ds-public.key"
        or die("failed to open public key for signature verification");

    my $keystr = <$keyfile>;
    close $keyfile;

    # load the public key into OpenSSL
    return Crypt::OpenSSL::RSA->new_public_key($keystr);
}

our $signage_key = load_key( );

sub install_config {
    my $file_data = shift;
    my $target = shift;
    my $outfile;
    unless (open $outfile, ">", $target) {
        print "warning: failed to write config to $target\n";
        return;
    }

    #print $outfile $file_data;
    #close $outfile;
}

sub find_configs {
    my $summary = shift;
    while ($summary =~ /^config_override:([^:]+):([^:]+):(.*)$/gm) {
        my $file = LWP::Simple::get($3); # get the data
        my $target = $1;
        my $sig = $2;
        if ($signage_key->verify($file, decode_base64($2))) {
            print "signature is good\n";
            print "(installing this to $target)\n";
            print "-->\n";
            print $file;
            print "\n<--\n";
            install_config($file, $target);
        } else {
            print "signature is not good (discarding file)\n";
        }
    }
}

sub configure {
    my $summary = get_summary(find_mac("eth0"));
    find_configs($summary);
}

1;
