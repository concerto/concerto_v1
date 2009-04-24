<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technolgies Group)
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
 * @author       Web Technologies Group, $Author: mike $
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision: 551 $
 */
header("Refresh: 5; URL=index.php?mac={$_GET['mac']}");
$tmp = str_split(str_pad(preg_replace('/[^0-9A-Fa-f]/', '', $_GET['mac']), 12, "0", STR_PAD_LEFT), 2);
$mac = sprintf("%s:%s:%s:%s:%s:%s", $tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5])
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Digital Signage</title>
<style type="text/css">
@font-face {
  font-family: "fixedsys";
  src: url(fixedsys.ttf) format("truetype");
}

body, html {
    height: 100%;
	width: 100%;
}

body {
	margin: 0;
	font-family: monospace;
	font-size: 12pt;
	background: #00a;
	color: #fff;
}

#outer {height: 100%; margin-left: auto; margin-right: auto;}
#outer[id] {display: table; position: static;}
#middle[id] {display: table-cell; vertical-align: middle; position: static;}
* html #outer {text-align: center;} /* for explorer only*/
* html #middle {position: absolute; top: 50%; text-align: left;} /* for explorer only*/
* html #inner {position: relative; top: -50%; left: -50%;} /* for explorer only */

span.title {
background: #bdbcb9;
color: #00a;
padding: 2px 2px;
font-weight: bold;
}

.center {
text-align: center;
}
</style>
</head>
<body>
<div id="outer">
	<div id="middle">
		<div id="inner">
			<table>

				<tr><td class="center"><span class="title">&nbsp;Concerto&nbsp;</span></td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						The MAC address <?= $mac ?> cannot be found	in the database.<br/>
						Please verify	that this screen has been added to the system.
					</td>
				</tr>

				<tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						* This page will refresh every 5 seconds.<br/>
						* Press CTRL+ALT+DEL again to restart your computer. You will<br/>
						&nbsp;&nbsp;lose unsaved information in any programs that are running.
					</td>
				</tr>

				<tr><td>&nbsp;</td></tr>
				<tr><td align="center">Press any key to continue _</td></tr>
			</table>
		</div>
	</div>
</div>
</body>
</html>

