<?php
include('../config.inc.php');

include(COMMON_DIR.'mysql.inc.php');
include(CONTENT_DIR.'render/render.php');

error_reporting(0);

$screen_id = escape($_GET['id']);
$sql = "SELECT template.filename, screen.width, screen.height FROM screen LEFT JOIN template ON screen.template_id = template.id WHERE screen.id = $screen_id LIMIT 1;";

$res = sql_query($sql);
$row = sql_row_keyed($res, 0);

render('template', $row['filename'], $row['width'], $row['height'], true);
?>
