<?php
include("../config.inc.php");
include(COMMON_DIR."/mysql.inc.php");
$screen_id = escape($_GET['id']);
$sql = "SELECT HEX(mac_address) FROM screen WHERE id = $screen_id LIMIT 1;";
$mac = sql_query1($sql);
header("Location: index.php?mac=$mac");
exit(0);

