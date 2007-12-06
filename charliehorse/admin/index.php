<?php

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

if (array_key_exists("action", $_REQUEST)) {
    $action = $_REQUEST["action"];
    switch ($action) {
        case "create":
            do_create( );
            break;
        case "delete":
            do_delete( );
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
    </body>
</html>

