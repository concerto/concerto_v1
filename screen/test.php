<?php
include("mysql.inc");
$sql = "SELECT HEX(mac_address) FROM screen WHERE id = {$_GET['id']} LIMIT 1;";
$mac = sql_query1($sql);
header("Location: index.php?mac=$mac");
exit(0);

