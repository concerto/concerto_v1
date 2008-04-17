<?
//I run the cron job for Concerto.
//Mainly I update rss feeds and dynamic content.
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
include(COMMON_DIR.'notification.php');//Image library, used for resizing images

include(CONTENT_DIR.'render/render.php'); //Functions to generate the cache
include(COMMON_DIR.'scripts/mail.php'); //Used to do the email magic

if(date("D Hi") == 'Sun 0010' || $_REQUEST['weekly']){
	weekly();
	print("Finished weekly job\n");
}
if(date("Hi") == '0010' || $_REQUEST['nightly']){
	nightly();
	print("Finished nightly job\n");
}
if(date("i") == '10' || $_REQUEST['hourly']){
	hourly();
	print("Finished hourly job");
}
always();

function weekly(){
	clear_cache(IMAGE_DIR.'/cache/');
	clear_cache(TEMPLATE_DIR.'/cache/');
}
function nightly(){

	//Cache any content we need to cache
	cache_parse(100);
}
function hourly(){

}
function always(){
	//First generate new content for dynamic feeds
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
	//go_mail();
}
?>
