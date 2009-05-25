<?
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technolgies Group)
 *
 * This program is free software; you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.  You should have received a copy
 * of the GNU General Public License along with this program.
 *
 * @package      Concerto
 * @author       Web Technologies Group, $Author: mike $
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision: 551 $
 */
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
	
  echo "Finding expired content in moderation queue...";
  deny_expired();
  echo "Done dening expired content in mod queue.\n";

}
function hourly(){
	//Rotate any screens that need a template rotation every 6 hours
	if(date('H') % 6 == 0) {
		if(is_array($scren_rotate)){
			echo "Executing template rotation.\n";
			foreach($screen_rotate as $key => $templates){
				$scr = new Screen($key);
				$templates = remove_element($templates, $scr->template_id);
				$new_key = array_rand($templates,1);
				$scr->template_id = $templates[$new_key];
				$scr->set_properties();
			}
			echo "Template rotation complete.\n";
		}
		echo "Hourly job complete.\n";
	}
	//End template rotation
}
function always(){
	//First generate new content for dynamic feeds
	$feed_handler = new Feed();
	if($feeds = $feed_handler->get_all("WHERE type = 1 OR type = 4")){
		foreach($feeds as $feed){
			echo "Calling $feed->name for update. \n";
			if($feed->dyn->update()){
				echo "Updated $feed->name OK\n";
			} else {
				echo "Error updating $feed->name\n";
				print_r($feed);
		  }
		  echo "Status: " . $feed->dyn->status . "\n";
		  
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

function screen_offline_mail( ) {
    # Query all screens and mail a report if any are offline
    $screens = Screen::get_all( );
    if(!is_array($screens)){
      return false;
    }
    $downed_screens = Array( );
    foreach ($screens as $screen) {
        if ($screen->went_down()) {
            $downed_screens[] = $screen;
        }
    }

    # construct email report if any screens have gone down in last 2 hours
    if (count($downed_screens) > 0) {
        $admin = new Group(ADMIN_GROUP_ID);

        $mail_body = "The following Concerto screens have gone offline. Please investigate.\n";
        //$mail_body .= "NOTE: THIS EMAIL IS A TEST, IT IS ONLY A TEST. FEEL FREE TO IGNORE.\n";
        foreach ($downed_screens as $screen) {
            $name = $screen->name;
            echo "Found downed screen $name.\n";
            $location = $screen->location;
            $mac = $screen->mac_inhex;

            $mail_body .= "$name (at $location, mac $mac)\n";
        }
       
       $admin->send_mail("Screen Outage Detected", $mail_body);
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

//Scan for content still in the moderation queue that has expired and deny it
function deny_expired(){
  $notification = "The content was denied automatically.  A moderator did not review this item before the expiration date.";
  $feeds = Feed::get_all();
  foreach($feeds as $feed){
    $contents = $feed->content_get('NULL'); //Content that hasn't been moderated
    if($contents){
      foreach($contents as $content){
        if(strtotime($content['content']->end_time) < strtotime('NOW')){
          $feed->content_mod($content['content']->id, 0, 0, $content['content']->get_duration($feed),$notification);
          echo "Denied {$content['content']->name} on {$feed->name}\n";
        }
      }
    }
  }
}

?>
