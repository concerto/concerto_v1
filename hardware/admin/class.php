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

require_once("dbfuncs.php");

init_db( );

function do_rename($class_obj) {
    if (!array_key_exists("new_name", $_REQUEST)) {
        die("go away loser");
    }
    $class_obj->rename($_REQUEST["new_name"]);    
}

function do_add_mem($class_obj) {
    if (!array_key_exists("mac", $_REQUEST)) {
        die("go away loser");
    }
    $class_obj->add_member($_REQUEST["mac"]);    
}

function do_delete($class_obj) {
    $class_obj->remove( );
}

function do_delete_members($class_obj) {
    if (!array_key_exists("selection", $_REQUEST)) {
        die("go away loser");
    }
    if (!is_array($_REQUEST["selection"])) {
        die("go away loser");
    }
    foreach ($_REQUEST["selection"] as $mac) {
        $class_obj->remove_member($mac);
    }
}

function do_delete_override($class_obj) {
    if (!array_key_exists("path", $_REQUEST)) {
        die("go away loser");
    }
    $class_obj->remove_override($_REQUEST["path"]);
}

function do_create_override($class_obj) {
    if (!array_key_exists("path", $_REQUEST)) {
        die("go away loser");
    }
    $class_obj->add_override($_REQUEST["path"]);
}

function do_add_file($class_obj) {
    if (!array_key_exists("name", $_REQUEST) || !array_key_exists("target_path", $_REQUEST)) {
        die("parameters not good");
    }
    $class_obj->add_file($_REQUEST["name"], $_REQUEST["target_path"]);
}

function do_remove_file($class_obj) {
    if (!array_key_exists("name", $_REQUEST)) {
        die("bad parameters");
    }
    $class_obj->remove_file($_REQUEST["name"]);
}

$class_id = $_REQUEST["class"];
if (!is_numeric($class_id)) {
    die("go away, loser");
}

$class_obj = HardwareClass::load_from_id($class_id);

if (array_key_exists("action", $_REQUEST)) {
    $action = $_REQUEST["action"];
    switch ($action) {
        case "rename":
            do_rename($class_obj);
            break;
        case "delete":
            do_delete($class_obj);
            break;
        case "add_member":
            do_add_mem($class_obj);
            break;
        case "remove_members":
            do_delete_members($class_obj);
            break;
        case "delete_over":
            do_delete_override($class_obj);
            break;
        case "create_over":
            do_create_override($class_obj);
            break;
        case "add_file":
            do_add_file($class_obj);
            break;
        case "delete_file":
            do_remove_file($class_obj);
            break;
    }
}



?>
<html>
    <head>
        <title>Edit Hardware Class: 
            <?php print $class_obj->get_name( ); ?>
        </title>
    </head>
    <body>
        <h1>Editing class: <?php print $class_obj->get_name( ); ?></h1>
        <h2>General Administration</h2>
        <form action="<?php print $_SERVER['PHP_SELF']?>" method="post">
            <p>
                Rename To: <input type="text" name="new_name" />
                <input type="hidden" name="action" value="rename" />
                <input type="hidden" name="class" value="<?=$class_id ?>" />
                <input type="submit" value="Go!" />
            </p>
        </form>
        <hr />
        <h2>Class Members</h2>
        <p>
<?php
    $members = $class_obj->get_member_list( );
    if (count($members) > 0) {
        // print table header
        print '<form action="'.$_SERVER['PHP_SELF'].'" method="post">';
        print "<table border=1>";
        print "<tr><td><b>Select</b></td>";
        print "<td><b>MAC Address</b></td></tr>";
        // print some entries
        foreach ($members as $mac) {
            print "<tr><td>";
            print "<input type=\"checkbox\" name=\"selection[]\" value=\"$mac\" />";
            print "</td><td>$mac</td></tr>";
        }
        // print table footer
        print "</table>";
        print '<input type="hidden" name="class" value="'.$class_id.'" />';
        print '<input type="hidden" name="action" value="remove_members" />';
        print '<input type="submit" value="Remove Members" />';
        print "</form>";
    } else {
        print "<p>No Members</p>";
    }
?>
        </p>
        <form action="<?php print $_SERVER['PHP_SELF']?>" method="post">
        <p>
                Input MAC Address: <input type="text" name="mac" />
                <input type="hidden" name="action" value="add_member" />
                <input type="hidden" name="class" value="<?=$class_id ?>">
                <input type="submit" value="Add Member!" />
        </p>
        </form>
        <hr />
        <h2>Configuration Overrides</h2>
<?php
    $overrides = $class_obj->list_overrides( );
    //$overrides = array("/etc/X11/xorg.conf", "/etc/resolv.conf");
    if (count($overrides) > 0) {
        foreach ($overrides as $path) {
            $pathhtml = htmlspecialchars($path);
            $pathurl = urlencode($path);
            print "<p>$path: ";
            print "<a href=\"override.php?class=" . $class_obj->get_id( ) 
                . "&path=" . $pathurl . "&action=edit\">Edit</a> | ";
            print "<a href=\"class.php?class=" . $class_obj->get_id( ) 
                . "&path=" . $pathurl . "&action=delete_over\">Delete</a>";
            print "</p>";
        }
    } else {
        print "<p>No Overrides</p>";
    }
?>
    <form action="class.php" method="post">
    <p>
        Create New Override for file: <input type="text" name="path" />
        <input type="hidden" name="class" 
            value="<?php print $class_obj->get_id( ); ?>" />
        <input type="hidden" name="action" value="create_over" />
        <input type="submit" value="Go!" />
    </p>
    </form>
    <hr />
    <h2>Flash Files</h2>
<?php
    $files = $class_obj->list_files( );
    foreach ($files as $file) {
        $name = $file["name"];
        $path = $file["path"];

        print "<p>$name as $path: ";
        print "<a href=\"class.php?class=".$class_obj->get_id( ).
            "&name=" . $path . "&action=delete_file\">Delete</a>";
        print "</p>";
    }
?>
    <form action="class.php" method="post">
    <p>
        Send file:
        <select name="name">
<?php
        $dh = opendir(BASE_DIR."/flash");
        while (($filename = readdir($dh)) !== false) {
            $path = BASE_DIR."/flash/$filename";
            if (is_file($path)) {
                print "<option value=\"$filename\">$filename</option>";
            }
        }
?>
        </select> as <input type="text" name="target_path" />: 
        <input type="submit" value="Do It!" />
        <input type="hidden" name="action" value="add_file">
        <input type="hidden" name="class" value="<?=$class_obj->get_id( ); ?>"/>
    </form>
    </body>
</html>

