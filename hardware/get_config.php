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
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */

# This returns a file with multiple lines indicating the various
# possibilities for individualized configuration.
# These are documented below.

# system_mac:$mac - echoes back the MAC of the requesting system
# hardware_class:$class - echoes the numeric class ID of the requestor
# config_override:$path:$sig:$url - a configuration file override.
# This is followed by a URL where the override can be retrieved,
# and a base64-encoded RSA signature of the file.
# kernel:$sig:$url - location and signature of the latest kernel image
# image:$sig:$url - location and signature of the latest software image

require_once("dbfuncs.php");
init_db( );

if (!array_key_exists("class", $_REQUEST)) {
    die("hardware class unspecified");
}

if (($class = HardwareClass::load_from_id($_REQUEST["class"])) == 0) {
    die("hardware class invalid");
}

if (!array_key_exists("path", $_REQUEST)) {
    die("config file unspecified");
}

if (($override = $class->get_override($_REQUEST["path"])) === 0) {
    die("override does not exist");
}

print $override;

?>
