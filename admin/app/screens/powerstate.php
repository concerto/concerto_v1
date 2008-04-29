<?php
require(ROOT_DIR.'/hardware/config.php');
require(ROOT_DIR.'/hardware/signature.php');

$resp = generate_signature($this->challenge);
print("$resp\n");
print($this->status?"on\n":"off\n");
?>
