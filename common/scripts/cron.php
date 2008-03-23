<?
//I run the cron job for Concerto.
//Mainly I update rss feeds and dynamic content.
include('../../config.inc.php');

include(COMMON_DIR.'/mysql.inc.php');//Tom's sql library interface + db connection settings
include(COMMON_DIR.'/user.php');     //Class to represent a site user
include(COMMON_DIR.'/screen.php');   //Class to represent a screen in the system
include(COMMON_DIR.'/feed.php');     //Class to represent a content feed
include(COMMON_DIR.'/field.php');    //Class to represent a field in a template
include(COMMON_DIR.'/position.php'); //Class to represent a postion relationship
include(COMMON_DIR.'/content.php');  //Class to represent content items in the system
include(COMMON_DIR.'/upload.php');   //Helps uploading
include(COMMON_DIR.'/group.php');    //Class to represent user groups
include(COMMON_DIR.'/dynamic.php');  //Functionality for dynamic content
include(COMMON_DIR.'/image.inc.php');//Image library, used for resizing images


$feed_handler = new Feed();
if($feeds = $feed_handler->get_all("WHERE type = 1")){
	foreach($feeds as $feed){
		if($feed->dyn->update()){
			echo "Updated $feed->name OK\n";
		} else {
			echo "Error updating $feed->name\n";
		}
	}
}
?>
