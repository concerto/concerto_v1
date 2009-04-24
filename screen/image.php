<?php
include('../config.inc.php');

include(COMMON_DIR.'mysql.inc.php');
include(CONTENT_DIR.'render/render.php');

error_reporting(0);

render('image',$_GET['file'], $_GET['width'], $_GET['height']);
?>
