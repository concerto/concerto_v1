<?php
include("../config.inc.php");
include(COMMON_DIR."mysql.inc.php");
include(COMMON_DIR."driver.php");

error_reporting(0);

if(isset($_POST['id'])){
    $driver = new ScreenDriver($_POST['id']);
    $json = $driver->screen_details();
    if($json) $json["checksum"] = crc32(json_encode($json));
    echo json_encode($json);
} elseif(isset($_POST['screen_id']) && isset($_POST['field_id'])) {
    $driver = new ContentDriver($_POST['screen_id'], $_POST['field_id']);
    $data = $driver->content_details();
    echo json_encode($data);
} else {
    echo json_encode(NULL);
}
?>
