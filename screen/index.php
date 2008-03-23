<?php
include("../config.inc.php");
include(COMMON_DIR."/mysql.inc.php");

if(!isset($_GET['mac'])) $_GET['mac'] = 0;
$mac = hexdec($_GET['mac']);
$sql = "SELECT id FROM screen WHERE mac_address = $mac LIMIT 1;";
$id = sql_query1($sql);
if($id < 0){
    header("Location: missing.php?mac={$_GET['mac']}");
    exit(0);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Digital Signage</title>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="signage.js"></script>
<script type="text/javascript"><!--
screenId = <?= $id ?>;//--></script>
</head>
<body style="overflow: hidden">
</body>
</html>
