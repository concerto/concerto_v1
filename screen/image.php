<?php
include("../config.inc.php");

include(COMMON_DIR."mysql.inc.php");
include(COMMON_DIR."image.inc.php");

error_reporting(0);

resize(IMAGE_DIR.$_GET['file'], $_GET['width'], $_GET['height']);
?>
