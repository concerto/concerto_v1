<?php

require_once("hexdump.php");

function generate_signature($data) {
    # generate base64 encoded RSA signature for data
    $fp = fopen(PRIVATE_KEY_PATH, "r")
        or die("failed to load private key file!");
    $pktext = fread($fp, 65536); # private key should be no larger than this
    $privkey = openssl_get_privatekey($pktext)
        or die("failed to load private key data from file: " 
            . openssl_error_string( ));

    $sig = "";
    if (!openssl_sign($data, $sig, $privkey)) {
        die("Failed to digitally sign data: " . openssl_error_string( ));
    }

    return base64_encode($sig);
    
}

?>
