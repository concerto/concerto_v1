<?
include('../../config.inc.php');

include(COMMON_DIR.'mysql.inc.php');//Tom's sql library interface + db connection settings
include(COMMON_DIR.'user.php');     //Class to represent a site user
include(COMMON_DIR.'screen.php');   //Class to represent a screen in the system
include(COMMON_DIR.'feed.php');     //Class to represent a content feed
include(COMMON_DIR.'field.php');    //Class to represent a field in a template
include(COMMON_DIR.'position.php'); //Class to represent a postion relationship
include(COMMON_DIR.'content.php');  //Class to represent content items in the system
include(COMMON_DIR.'upload.php');   //Helps uploading
include(COMMON_DIR.'group.php');    //Class to represent user groups
include(COMMON_DIR.'dynamic.php');  //Functionality for dynamic content
include(COMMON_DIR.'image.inc.php');//Image library, used for resizing images
include(COMMON_DIR.'notification.php');//Class to represent notifications


 set_time_limit(0);

$notif = new Notification();
if(!isset($_REQUEST['process']) || !is_numeric($_REQUEST['process'])){
  $notif->process(10000);
} else {
  $notif->process($_REQUEST['process']);
}
echo "done";
?>
