<?php


$to_sign = "the quick brown fox jumped over the lazy dog";

$fp = fopen("/var/www/ds-dev/charliehorse/digisignage-dev.key", "r");
$priv_key = fread($fp, 8192);
fclose($fp);
$pkeyid = openssl_get_privatekey($priv_key);

$sig="";
if (openssl_sign($to_sign, $sig, $pkeyid)) {
    print base64_encode($sig);
} else {
    print "signing failed!\n";
}


$fp = fopen("/var/www/ds-dev/charliehorse/digisignage-dev-public.key", "r");
$public_key = fread($fp, 65536);
fclose($fp);
$pkeyid = openssl_get_publickey($public_key);

$sig="";
$result = openssl_verify($to_sign, $sig, $pkeyid);

if ($result == 1) {
    print "signature OK\n";
} else if ($result == 0) {
    print "signature bad!\n";
} else {
    print "failed to verify!\n";
}

?>
