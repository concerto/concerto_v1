<?php

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
        die("go away loser (0)");
    }
    if (!is_array($_REQUEST["selection"])) {
        die("go away loser (1)");
    }
    foreach ($_REQUEST["selection"] as $mac) {
        $class_obj->remove_member($mac);
    }
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
    </body>
</html>

