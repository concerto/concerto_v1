<?php
include("../config.inc.php");
include(COMMON_DIR."mysql.inc.php");
include(COMMON_DIR."driver.php");

//Default Values
$format = 'json';
//End default values

//Define acceptable values
$format_av = array('json','var_val','xml');
//End Acceptable values

//Grab and check user values
if(isset($_REQUEST['format']) && in_array($_REQUEST['format'],$format_av)){
        $format = $_REQUEST['format'];
}


if(isset($_GET['id'])){
    $driver = new ScreenDriver($_GET['id']);
    $json = $driver->screen_details();
    if($json) $json["checksum"] = crc32(json_encode($json));
    if($format == 'json'){
       echo json_encode($json);
    }elseif($format == 'var_val'){
       echo var_val($json);
    }elseif($format == 'xml'){
       echo toXml($json);
    }
} elseif(isset($_GET['screen_id']) && isset($_GET['field_id'])) {
    $driver = new ContentDriver($_GET['screen_id'], $_GET['field_id']);
    $data = $driver->content_details();
    if($format == 'json'){
       echo json_encode($data);
    }elseif($format == 'var_val'){
       echo var_val($data);
    }elseif($format == 'xml'){
       echo toXml($data);
    }
} else {
    echo json_encode(NULL);
}
?>
