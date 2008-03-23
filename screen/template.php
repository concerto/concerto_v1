<?php
include("../config.inc.php");

include(COMMON_DIR."mysql.inc.php");
include(COMMON_DIR."image.inc.php");

error_reporting(0);

$sql = "SELECT template.filename, screen.width, screen.height FROM screen LEFT JOIN template ON screen.template_id = template.id WHERE screen.id = {$_GET['id']} LIMIT 1;";
$res = sql_query($sql);
$row = sql_row_keyed($res, 0);

resize(TEMPLATE_DIR.$row['filename'], $row['width'], $row['height'], true);
?>
