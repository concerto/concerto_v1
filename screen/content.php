<?php
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
