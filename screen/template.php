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
include('../config.inc.php');

include(COMMON_DIR.'mysql.inc.php');
include(CONTENT_DIR.'render/render.php');

error_reporting(0);

$screen_id = escape($_GET['id']);

if (!is_numeric($screen_id)) {
    die("Screen ID must be numeric");
}

$sql = "SELECT template.filename, screen.width, screen.height FROM screen LEFT JOIN template ON screen.template_id = template.id WHERE screen.id = $screen_id LIMIT 1;";

$res = sql_query($sql);
$row = sql_row_keyed($res, 0);

render('template', $row['filename'], $row['width'], $row['height'], true);
?>
