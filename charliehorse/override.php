<?php

require_once("dbfuncs.php");
init_db( );


$class_id = $_REQUEST["class"];
if (!is_numeric($class_id)) {
    die("go away, loser");
}

function do_write($class_obj, $path) {
    if (!array_key_exists("text", $_REQUEST)) {
        die("text not specified for config write");
    }
    $class_obj->edit_override($path, $_REQUEST["text"]);
}

$class_obj = HardwareClass::load_from_id($class_id);

if (!array_key_exists("path", $_REQUEST)) {
    die("override path not specified");
}
$path = $_REQUEST["path"];

if (array_key_exists("action", $_REQUEST)) {
    $action = $_REQUEST["action"];
    switch ($action) {
        # handle different actions
        case "write":
            do_write($class_obj, $path);
    }
}

?>
<html>
    <head>
        <title>Edit Override: file 
            <?php print $path; ?>
            in class
            <?php print $class_obj->get_name( ); ?>
        </title>
    </head>
    <body>
        <h1>
        Editing Override: file 
        <?php print $path; ?>
        in class
        <?php print $class_obj->get_name( ); ?>
        </h1>

        <form action="override.php" method="post">
            <input type="hidden" name="action" value="write" />
            <input type="hidden" name="class"
                value="<?php print $class_obj->get_id( ) ?>" />
            <input type="hidden" name="path" value="<?=$path?>" />
            <p>
                <textarea name="text" style="width: 100%; height: 80%"><?php print $class_obj->get_override($path); ?></textarea>
            </p>
            <p>
                Please confirm that this input is correct, and will not expose
                the system to security vulnerabilities! Once this
                configuration has been digitally signed, all machines will
                accept this file irrevocably until the private key is changed.
            </p>
            <p><input type="submit" value="Sign and Save" /></p>
        </form>
        <hr />
        <a href="class.php?class=<?php print $class_obj->get_id( ); ?>">
            Return to Class: <?php print $class_obj->get_name( ); ?>
        </a>
    </body>
</html>

