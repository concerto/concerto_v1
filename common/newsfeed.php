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
Class: Newsfeed
Status: New
Functionality:
      hide            hides an item on a newsfeed
      unhide          unhides an item on a newsfeed
      toggle_hidden   Does a litle of both the above
      get_for_user    Given a user id, will return all the items matching some criteria
Comments:		The companion for notifications.
Used to get the newsfeed items and interact with them as users
*/
class Newsfeed{
	var $id;
	var $activity_date;
	var $hidden;
	var $text;
	var $type;
	var $msg;
	
	var $set;

  function __construct($id = ''){
    if($id == '' || !is_numeric($id)){
			$this->set = false;
		} else {
      $sql = 'SELECT newsfeed.id, notifications.text, notifications.timestamp, notifications.type, notifications.msg, notifications.additional, newsfeed.hidden FROM `newsfeed` LEFT JOIN notifications ON newsfeed.notification_id = notifications.id WHERE newsfeed.id = ' . $id;
      $res = sql_query($sql);
      if($res != 0){
				$data = (sql_row_keyed($res,0));
      
        $this->id = $data['id'];
        $this->timestamp = strtotime($data['timestamp']);
        $this->type = $data['type'];
        $this->msg = $data['msg'];
        $this->hidden = $data['hidden'];
        $this->text = $data['text'];
        $this->additional = $data['additional'];
        $this->text = str_replace('%ADMIN_URL%', ADMIN_URL, $this->text); //If the URL changes, we want to be ready!
        
        //A simple boolean to check if there is extra data to show
        $this->has_extra = false;
        
        if($this->additional > "" || strlen($this->additional) > 0){
            $this->has_extra = true;
        }
        
        $this->set = true;
      } else {
        $this->set = false;
      }
    }
  }
  
  function hide(){
    if($this->set){
      $sql = 'UPDATE newsfeed SET hidden = 1 WHERE id = ' . $this->id;
      sql_command($sql);
      return true;
    } else {
      return false;
    }
  }
  
  function unhide(){
    if($this->set){
      $sql = 'UPDATE newsfeed SET hidden = 0 WHERE id = ' . $this->id;
      sql_command($sql);
      return true;
    } else {
      return false;
    }
  }
  
  function toggle_hidden(){
    if($this->set){
      if($this->hidden){
        return $this->unhide();
      } else{
        return $this->hide();
      }
    } else {
      return false;
    }
  }
  
  function count_for_user($user_id, $hidden = 0, $since = ''){
    if(!is_numeric($user_id)){
      return false;
    }
    $hide_string = '';
    if($hidden !== ''){
      if(is_numeric($hidden)){
        $hide_string = ' AND `hidden` = ' . $hidden;
      } else {
        return false;
      }
    }

    $ts_string = '';
    if($since != ''){
      if($timestamp = strtotime($since)){
        $ts_string = " AND `timestamp` > '" . date("Y-m-d G:i:s", $timestamp) . "' ";
      } else {
        return false;
      }
    }
    $sql = 'SELECT COUNT(newsfeed.id) as Count FROM newsfeed LEFT JOIN notifications ON newsfeed.notification_id = notifications.id WHERE user_id = ' . $user_id . $hide_string . $ts_string;
    $res = sql_query($sql);
    if($res != 0 && $row = sql_row_keyed($res,0)){
      return $row['Count'];
    }
    return 0;
  }
  function get_for_user($user_id, $hidden = 0, $since= '', $offset=0,$count = 5){
    if(!is_numeric($user_id) || !is_numeric($offset) || !is_numeric($count)){
      return false;
    }
    $hide_string = '';
    if($hidden !== ''){
      if(is_numeric($hidden)){
        $hide_string = ' AND `hidden` = ' . $hidden;
      } else {
        return false;
      }
    }

    $ts_string = '';
    if($since != ''){
      if($timestamp = strtotime($since)){
        $ts_string = " AND `timestamp` > '" . date("Y-m-d G:i:s", $timestamp) . "' ";
      } else {
        return false;
      }
    }
    
    $sql = 'SELECT newsfeed.id FROM newsfeed LEFT JOIN notifications ON newsfeed.notification_id = notifications.id WHERE user_id = ' . $user_id . $hide_string . $ts_string . ' ORDER BY notifications.timestamp  DESC LIMIT ' . $offset . ' , ' . $count;
    
    $res = sql_query($sql);
    $notifs = array();
    if($res != 0){
      $i = 0;
      while($row = sql_row_keyed($res, $i)){
        $notifs[] = new Newsfeed($row['id']);
        $i++;
      }
    }
    return $notifs;
  }
}
?>
