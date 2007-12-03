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

?>
