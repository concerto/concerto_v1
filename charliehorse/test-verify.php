<?php


$to_sign = "the quick brown fox jumped over the lazy dog";

$fp = fopen("/var/www/ds-dev/charliehorse/ds-dev-private.key", "r");
$priv_key = fread($fp, 8192);
fclose($fp);
$pkeyid = openssl_get_privatekey($priv_key);

$sig="";
if (!openssl_sign($to_sign, $sig, $pkeyid)) {
    print "signing failed!\n";
}


$fp = fopen("/var/www/ds-dev/charliehorse/ds-dev-public.key", "r");
$public_key = fread($fp, 65536);
fclose($fp);
$pkeyid = openssl_get_publickey($public_key);

print "verifying unaltered message...\n";
$result = openssl_verify($to_sign, $sig, $pkeyid);

if ($result == 1) {
    print "signature OK\n";
} else if ($result == 0) {
    print "signature bad!\n";
} else {
    print "failed to verify!\n";
}

print "trying altered message...\n";
// change the message around a bit so it won't verify
$to_sign = "the quick brown fox jumped over the lazy dogs";
$result = openssl_verify($to_sign, $sig, $pkeyid);

if ($result == 1) {
    print "signature OK\n";
} else if ($result == 0) {
    print "signature bad!\n";
} else {
    print "failed to verify!\n";
}

?>
