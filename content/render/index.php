<?
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
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */
include_once('../../config.inc.php');
include_once(COMMON_DIR.'mysql.inc.php');
include_once('render.php');

$current_stable = '008';

//Routes you to the correct API Version
if(isset($_REQUEST['api']) && is_numeric($_REQUEST['api'])){
	$api_v = $_REQUEST['api'];
} else {
	$api_v = $current_stable;
}
if(file_exists('api/' . $api_v . '.php')){
	require_once('api/' . $api_v . '.php');
} else {
	require_once('api/' . $current_stable . '.php');
}




?>
