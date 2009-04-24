<?php

require_once("dbfuncs.php");
$chal = $_REQUEST["challenge_string"];
$resp = generate_signature($chal);
$localtime = localtime( );

print("$resp\n");
if ($localtime[2] < 7) {	
    print("off\n");
} else {
    print("on\n");
}

?>
