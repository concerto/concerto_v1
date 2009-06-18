<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technologies Group)
 *
 * This program is free software; you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.  You should have received a copy
 * of the GNU General Public License along with this program.
 *
 * @package      Concerto
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */
include("../config.inc.php");
include(COMMON_DIR."mysql.inc.php");

if(!isset($_GET['mac'])) $_GET['mac'] = 0;
$mac = hexdec($_GET['mac']);
$sql = "SELECT id FROM screen WHERE mac_address = $mac LIMIT 1;";
$screenId = sql_query1($sql);
if($screenId < 0){
    if(isset($_REQUEST['livecd']) && $_REQUEST['livecd'] == 1){
        header('Location: ' . ADMIN_URL . '/screens/livecd?' . $_SERVER["QUERY_STRING"]);
        exit(0);
    }
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
$(document).ready(function(){$.signage(<?= $screenId ?>);});//--></script>
</head>
<body style="overflow: hidden">
</body>
</html>
