<?
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technologies Group)
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
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */
/*
Class: Notification
Status: New
Functionality:
      notify            creates a small notification in the system
      build             expands the small notification into a full text entry
      move              maps the notification to all the appropriate people
Comments:
To log a notification into the system, code call the notify function.
Later on, a cron job expands the simple notification (build) and distributes it (move)
*/
class Notification{
	var $id;
	var $timestamp;
	var $type;
	var $type_id;
	var $by;
	var $by_id;
	var $processed;
	var $msg;
	
	var $text;
	var $type_obj;
	var $by_obj;
	var $users;
	
	var $set;
	var $lost_items;

	function __construct($id = ''){
		if($id == '' || !is_numeric($id)){
			$this->set = false;
		} else {
			$sql = "SELECT * FROM `notifications` WHERE id = $id LIMIT 1";
			$res = sql_query($sql);
			if($res != 0){
				$data = (sql_row_keyed($res,0));
				$this->id = $id;
				$this->timestamp = $data['timestamp'];
				$this->type = $data['type'];
				$this->type_id = $data['type_id'];
				$this->by = $data['by_type'];
				$this->by_id = $data['by_id'];
				$this->processed = $data['processed'];
				$this->msg = $data['msg'];
				$this->additional = $data['additional'];
				$this->text = $data['text'];

				$this->set = true;				
			} else {
				$this->set = false;
			}
		}
	}
	
	//Log a notification in the table
	function notify($type_in, $type_id_in, $by_in, $by_id_in, $msg_in, $additional_in = ''){
		if(defined("NOTIF_OFF") && NOTIF_OFF == 1){ //Incase we don't want notifications
			return true;
		} else {
			$additional = escape($additional_in);
			$sql = "INSERT INTO `notifications` (`type`, `type_id`, `by_type`, `by_id`, `msg`,`additional`, `processed`, `timestamp`) 
			VALUES ('$type_in', $type_id_in, '$by_in', $by_id_in, '$msg_in', '$additional', 0, NOW())";
			$res = sql_query($sql);
			return true;
		}
	}
	
	//Builds the obj's from the types and ids for both type and by
	function build(){
		$users = array(); //Will hold all the users who get a notification
		//Handles "type"
		if($this->type == 'feed'){
			$this->type_obj = new Feed($this->type_id);
			$group = new Group($this->type_obj->group_id);
			if($group->set){
				$group_users = $group->list_members();
				if(is_array($group_users)){
					$users = array_merge($users, $group_users);
				}
			}
		} elseif($this->type == 'content'){
			$this->type_obj = new Content($this->type_id);
			$user = new User($this->type_obj->user_id);
			if($user->set){
				$users[] = $user->username;
			}
		} elseif($this->type == 'group'){
			$this->type_obj = new Group($this->type_id);
			if($this->type_obj->set){
				$users = array_merge($users, $this->type_obj->list_members());
			}
		} elseif($this->type == 'screen'){
			$this->type_obj = new Screen($this->type_id);
			$group = new Group($this->type_obj->group_id);
			if($group->set){
				$group_users = $group->list_members();
				$users = array_merge($users, $group_users);
			}
		} elseif($this->type == 'user'){
			$this->type_obj = new User($this->type_id);
			if($this->type_obj->set){
				$users[] = $this->type_obj->username;
			}
		} else {
			$this->type_obj = '';
		}
		
		//Handles "by"
		if($this->by == 'feed'){
			$this->by_obj = new Feed($this->by_id);
			$group = new Group($this->by_obj->group_id);
			if($group->set){
				$group_users = $group->list_members();
				$users = array_merge($users, $group_users);
			}
		} elseif($this->by == 'content'){
			$this->by_obj = new Content($this->by_id);
			$user = new User($this->by_obj->user_id);
			if($user->set){
				$users[] = $user->username;
			}
		} elseif($this->by == 'group'){
			$this->by_obj = new Group($this->by_id);
			if($this->by_obj->set){
				$members = $this->by_obj->list_members();
				if(is_array($members)){
					$users = array_merge($users, $members);
				}
			}
		} elseif($this->by == 'screen'){
			$this->by_obj = new Screen($this->by_id);
			$group = new Group($this->by_obj->group_id);
			if($group->set){
				$group_users = $group->list_members();
				$users = array_merge($users, $group_users);
			}
		} elseif($this->by == 'user'){
			$this->by_obj = new User($this->by_id);
			if($this->by_obj->set){
				$users[] = $this->by_obj->username;
			}
		} else {
			$this->by_obj = '';
		}
		
		//Populates the text with something
		$text['feed']['content']['add'] = '<a href="%ADMIN_URL%/content/show/%2_id">%2_name</a> has been submitted to the <a href="%ADMIN_URL%/browse/show/%1_id">%1_name</a> feed.';
		$text['feed']['content']['approve'] = '<a href="%ADMIN_URL%/content/show/%2_id">%2_name</a> has been approved on <a href="%ADMIN_URL%/browse/show/%1_id">%1_name</a> feed.';
		$text['feed']['content']['deny'] = '<a href="%ADMIN_URL%/content/show/%2_id">%2_name</a> has been denied on <a href="%ADMIN_URL%/browse/show/%1_id">%1_name</a> feed.';
		$text['feed']['user']['update'] = '<a href="%ADMIN_URL%/browse/show/%1_id">%1_name</a> feed has been updated by <a href="%ADMIN_URL%/users/show/%2_un">%2_name</a>.';
		$text['group']['user']['join'] = '<a href="%ADMIN_URL%/users/show/%2_un">%2_name</a> has joined the <a href="%ADMIN_URL%/groups/show/%1_id">%1_name</a> group.';
		$text['screen']['user']['update'] = '<a href="%ADMIN_URL%/users/show/%2_un">%2_name</a> has updated the <a href="%ADMIN_URL%/screens/show/%1_id">%1_name</a> screen.';
		$text['screen']['feed']['subscribe'] = '<a href="%ADMIN_URL%/screens/show/%1_id">%1_name</a> has been subscribed to the <a href="%ADMIN_URL%/browse/show/%2_id">%2_name</a> feed.';
		$text['user']['']['new'] = 'Welcome to Concerto, <a href="%ADMIN_URL%/users/show/%1_un">%1_name</a>';
		$text['feed']['group']['new'] = 'Hey look, the <a href="%ADMIN_URL%/browse/show/%1_id">%1_name</a> feed has been created.';
		$text['screen']['group']['new'] = 'The <a href="%ADMIN_URL%/screens/show/%1_id">%1_name</a> screen has been deployed.';
		
		//Certain items are applied to all users such as new screens and new feeds
		if($this->msg == 'new' && ($this->type == 'screen' || $this->type == 'feed')){
		  $sql = "SELECT username FROM user";
		  $res = sql_query($sql);
		  $i=0;
		  while($row = sql_row_keyed($res, $i)){
		    $users[] = $row['username'];
		    $i++;
		  }
		}
		
		$this->lost_items = false;
		if($temp_text = $text[$this->type][$this->by][$this->msg]){
			if($this->type_obj != '' && $this->type_obj->set && strlen($this->type_obj->name) > 0){
				$temp_text = str_replace('%1_name', $this->type_obj->name, $temp_text);
				$temp_text = str_replace('%1_id', $this->type_obj->id, $temp_text);
				$temp_text = str_replace('%1_un', $this->type_obj->username, $temp_text);
			} else {
				$this->lost_items = true;
			}
			if($this->by_obj != '' && $this->by_obj->set && strlen($this->by_obj->name) > 0){
				$temp_text = str_replace('%2_name', $this->by_obj->name, $temp_text);
				$temp_text = str_replace('%2_id', $this->by_obj->id, $temp_text);
				$temp_text = str_replace('%2_un', $this->by_obj->username, $temp_text);
			} else {
				$this->lost_items = true;
			}
			if(!$this->lost_items){
				$this->text = $temp_text;
				$this->users = array_unique($users);
			} else {
				$this->text = '';
			}
		}else{
			$this->text = '';
			$this->lost_items = true;
		}
		
		//Now update the item with the text if we have any
    if(!$this->lost_items){
		  $sql = 'UPDATE notifications SET text = \'' . escape($this->text) . '\' WHERE id = ' . $this->id;
		  sql_command($sql);
		}
		
	}
	function move(){
		if($this->lost_items || strlen($this->text)<=0){
			$sql = 'UPDATE `notifications` SET `processed` = 2 WHERE `id` = ' . $this->id;
			sql_command($sql);
			return false;
		} else {
		  if(!$this->processed){
        foreach($this->users as $username){
				  $user = new User($username);
				  $sql = 'INSERT INTO `newsfeed` (`notification_id`, `user_id`) VALUES (' . $this->id . ', ' . $user->id . ')';
				  sql_command($sql);
				}
				  $sql2 = 'UPDATE `notifications` SET `processed` = 1 WHERE `id` = ' . $this->id;
				  sql_command($sql2);
				
				return true; 
		  }
		}
	}
	
	function process($number = 100){
    if(!is_numeric($number)){
      return false;
    }
    $sql = "SELECT id FROM `notifications` WHERE `processed` = 0 ORDER BY `notifications`.`timestamp` ASC LIMIT 0, $number";
    $res = sql_query($sql);
    $i = 0;
    while($row = sql_row_keyed($res, $i)){
      $notif = new Notification($row['id']);
      $notif->build();
      $notif->move();
      $i++;
    }
    return true;
	}
}
?>
