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

require_once("dbfuncs.php");

init_db( );


function do_create( ) {
    if (!array_key_exists("name", $_REQUEST)) {
        print $_REQUEST;
        die("go away loser");
    }
    HardwareClass::create_new($_REQUEST["name"]);
}

function do_delete( ) {
    $class_id = $_REQUEST["class"];
    if (!is_numeric($class_id)) {
        die("go away, loser");
    }

    $class_obj = HardwareClass::load_from_id($class_id);
    
    $class_obj->remove( );
}

function do_upload( ) {
    if (!array_key_exists("name", $_REQUEST)) {
        die("bad form input");
    }
    if (!array_key_exists("file", $_FILES)) {
        die("bad file input");
    }

    $base_location = "/var/www/ds/hardware/flash";
    $url_base = "http://signage.union.rpi.edu/hardware/flash";

    # move the file to the correct location
    $origfn = basename($_FILES["file"]["name"]);
    if (file_exists("$base_location/$origfn")) {
        $ext = 0;
        while (file_exists("$base_location/$origfn.$ext")) {
            ++$ext;
        }
        $fn_out = "$base_location/$origfn.$ext";
        $url = "$url_base/$origfn.$ext";
    } else {
        $fn_out = "$base_location/$origfn";
        $url = "$url_base/$origfn";
    }
    # print a data dump
    var_dump($_FILES);
    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $fn_out)) {
        die("bad file??");
    }

    FlashFile::create_new($_REQUEST["name"], $fn_out, $url);
}

if (array_key_exists("action", $_REQUEST)) {
    $action = $_REQUEST["action"];
    switch ($action) {
        case "create":
            do_create( );
            break;
        case "delete":
            do_delete( );
            break;
        case "upload_file":
            do_upload( );
            break;
    }
}

?>
<html>
    <head>
        <title>
            Digital Signage Hardware Overview
        </title>
    </head>
    <body>
        <h1>Digital Signage Hardware Overview</h1>
        <h2>Current Hardware Classes</h2>
        <p>
<?php
    $classes = HardwareClass::load_all_from_db( );
    if (count($classes) > 0) {
        // print table header
        foreach ($classes as $class) {
            $id = $class->get_id( );
            print "<p>";
            print $class->get_name( );
            print " (id $id): ";
            print "<a href=\"class.php?class=$id\">Edit</a>";
            # we can't delete the default configuration
            if ($id != 1) {    
                print " | <a href=\"index.php?class=$id&action=delete\">";
                print "Delete</a>";
            }
            print "</p>";
        }
        // print table footer
    } else {
        print "<p>No Classes (this should not happen!)</p>";
    }
?>
        </p>
        <hr /><h2>Create New Group</h2>
        <form action="index.php" method="post">
        <p>
                New Group Name: <input type="text" name="name" />
                <input type="hidden" name="action" value="create" />
                <input type="submit" value="Create Class" />
        </p>
        </form>
    <!--
        <hr /><h2>FLASH File Upload</h2>
        <form enctype="multipart/form-data" action="index.php" method="post">
        <input type="hidden" name="action" value="upload_file" />
        <p>Upload File: <input type="file" name="file" /> as <input type="text" name="name" /><input type="submit" value="Upload File"></p>
        </form>
    -->
    </body>
</html>

