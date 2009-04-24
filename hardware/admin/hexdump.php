<?php

function dump_hex($str) {
    for ($i = 0; $i < strlen($str); ++$i) {
        printf("%02x ", ord(substr($str, $i, 1)));
    }
}

?>
