<?php
include("../config.inc.php");
include(COMMON_DIR."mysql.inc.php");
include(COMMON_DIR."driver.php");

error_reporting(0);

if(isset($_GET['id'])){
    $driver = new Driver($_GET['id']);
    $json = $driver->screen_details();
    $json["checksum"] = crc32(json_encode($json));
    echo json_encode($json);
} elseif(isset($_GET['screen_id']) && isset($_GET['field_id'])) {
    $driver = new Driver($_GET['screen_id'], $_GET['field_id']);
    $driver->get_feed();
    $driver->get_content();
    echo json_encode($driver->content_details());
} else {
    echo json_encode(NULL);
}
?>
