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
include('config.inc.php');
if(isset($_GET['mac'])) {
   header('Location: '.SCREEN_URL.'?'.$_SERVER["QUERY_STRING"]);
} else {
  if(defined('PREFERRED_DOMAIN')) {
    # If we are trying to get users to use a particular domain,
    # send them to the admin directory of that domain, saving
    # ourselves a request.
    header('Location: http://'.PREFERRED_DOMAIN.'/'.ADMIN_URL.'/');
  } else {
    header('Location: '.ADMIN_URL.'/');
  }
}
?>
<head>
<title>Concerto</title>
</head>
<body style="background-color:#069; height:100%">
<div style="text-align:center;position:relative; top:25%;">
<img src="admin/images/conc_bluebg.gif">
<p style="color:white; font-family:sans-serif; font-style:italic; font-size:0.85em">Welcome</p>
</div>
</body>

