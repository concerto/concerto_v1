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
include(COMMON_DIR.'notification.php');//Class to represent notifications

include(CONTENT_DIR.'render/render.php'); //Functions to generate the cache


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
	echo "Clearing cache directories.\n";
	clear_cache(IMAGE_DIR.'/cache/');
	clear_cache(TEMPLATE_DIR.'/cache/');
	echo "Cache cleared.\n";
}
function nightly(){
	//Rollover the feed-content table's statistics
	echo "Rolling over statistics...\n";
	$sql = "UPDATE `feed_content` SET `yesterday_count` = `display_count`";
	sql_command($sql);	

	//Rolloever the position table's statistics
	$sql = "UPDATE `position` SET `yesterday_count` = `display_count`";
	sql_command($sql);

	//Clear out the old data.  I tried making it 1 sql statement, but it didn't work consistently
	$sql = "UPDATE `position` SET `display_count` = 0";
	sql_command($sql);

	$sql = "UPDATE `feed_content` SET `display_count` = 0";
	sql_command($sql);
	echo "Statistic rollover complete.\n";

	echo "Parsing cache...\n";
	//Parse the cache!
        cache_parse(25);
	echo "Completed cache parsing.\n";

}
function hourly(){
	//Rotate any screens that need a template rotation every 6 hours
	if(date('H') % 6 == 0) {
		echo "Executing template rotation.\n";
		//The array should be setup as follows, $screen[screen_id][] = template_id
		//Make sure you have subscriptions setup!
		$screens[5][] = 1;
		$screens[5][] = 8;
		foreach($screens as $key => $templates){
			$scr = new Screen($key);
			$templates = remove_element($templates, $scr->template_id);
			$new_key = array_rand($templates,1);
			$scr->template_id = $templates[$new_key];
			$scr->set_properties();
		}
		echo "Template rotation complete.\n";
	}
	//End template rotation
}
function always(){
	//First generate new content for dynamic feeds
	$feed_handler = new Feed();
	if($feeds = $feed_handler->get_all("WHERE type = 1")){
		foreach($feeds as $feed){
			echo "Calling $feed->name for update. \n";
			if($feed->dyn->update()){
				echo "Updated $feed->name OK\n";
			} else {
				echo "Error updating $feed->name\n";
				echo "Status: " . $feed->dyn->status . "\n";
				print_r($feed);
			}
		}
	}
	//Then generate the newsfeed!
	echo "Begin notification processing...\n";
	$notif = new Notification();
	$notif->process(20); //If more than 20 are generated the system might be under high load.
        echo "Done processing notifications. \n";
        
        // Send email notifications if the screens are not online
        echo "Scanning for recently downed screens...\n";
        screen_offline_mail( );
        echo "Done scanning for downed screens.\n";
}


# Function to determine if a screen went down recently
function screen_went_down($screen) {
    $update_time = $screen->status(0); // get last update timestamp
    if ( strtotime($update_time) > strtotime('-30 seconds') ) {
        return false; // screen updated in last 30 seconds - it's not down
    } else if ( strtotime($update_time) < strtotime('-340 seconds') ) {
        return false; // screen last updated more than 5 minutes + 30 seconds ago - been down a while
                      // note that this creates a 10 second window every 5 minutes, in which a screen
                      // could go down causing 2 emails. Better this than missing one.
    } else {
        return true; // screen must have gone down in last 2 hours to fall through here
    }
}

function screen_offline_mail( ) {
    # Query all screens and mail a report if any are offline
    $screens = Screen::get_all( );
    $downed_screens = Array( );
    foreach ($screens as $screen) {
        if (screen_went_down($screen)) {
            $downed_screens[] = $screen;
        }
    }

    # construct email report if any screens have gone down in last 2 hours
    if (count($downed_screens) > 0) {
        $mail_body = "The following Concerto screens have gone offline. Please investigate.\n";
        $mail_body .= "NOTE: THIS EMAIL IS A TEST, IT IS ONLY A TEST. FEEL FREE TO IGNORE.\n";
        foreach ($downed_screens as $screen) {
            $name = $screen->name;
            echo "Found downed screen $name.\n";
            $location = $screen->location;
            $mac = $screen->mac_inhex;

            $mail_body .= "$name (at $location, mac $mac)\n";
        }
        # ripped off from user.php but t should work
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-Type: text/plain; charset="UTF-8"' . "\r\n";
        $headers .= "From: $from\r\n";
        $headers .= "Reply-To: $from\r\n";
        $headers .= 'X-Mailer: Concerto';

        # send the email out
        mail(SCREEN_OUTAGE_ADDRESS, "Screen Outage Detected", $mail_body, $headers);
    }
}

//Tiny helper function for the template rotation
function remove_element($arr, $val){
	foreach ($arr as $key => $value){
		if ($arr[$key] == $val){
			unset($arr[$key]);
		}
	}
	return $arr = array_values($arr);
}
?>
