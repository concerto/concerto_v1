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
include("../config.inc.php");
include(COMMON_DIR."mysql.inc.php");
include(COMMON_DIR."driver.php");
include(COMMON_DIR."feed.php");
include(COMMON_DIR."dynamic.php");
include(COMMON_DIR."screen.php");
//error_reporting(0);

if(isset($_REQUEST['id'])){
    $driver = new ScreenDriver($_POST['id']);
    $json = $driver->screen_details();
    if($json) $json["checksum"] = crc32(json_encode($json));
    echo json_encode($json);
} elseif(isset($_REQUEST['screen_id']) && isset($_REQUEST['field_id'])) {
    $driver = new ContentDriver($_REQUEST['screen_id'], $_REQUEST['field_id']);
    $driver->get_content();
    $driver->ems_check();
    $data = $driver->content_details();
    echo json_encode($data);
} else {
    echo json_encode(NULL);
}
?>
