<?
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
	
	var $set;

  function __construct($id = ''){
    if($id == ''){
			$this->set = false;
		} else {
      $sql = 'SELECT newsfeed.id, notifications.text, notifications.timestamp, notifications.type, notifications.msg, newsfeed.hidden FROM `newsfeed` LEFT JOIN notifications ON newsfeed.notification_id = notifications.id WHERE newsfeed.id = ' . $id;
      $res = sql_query($sql);
      if($res != 0){
				$data = (sql_row_keyed($res,0));
      
        $this->id = $data['id'];
        $this->timestamp = strtotime($data['timestamp']);
        $this->type = $data['type'];
        $this->msg = $data['msg'];
        $this->hidden = $data['hidden'];
        $this->text = $data['text'];
        $this->text = str_replace('%ADMIN_URL%', ADMIN_URL, $this->text); //If the URL changes, we want to be ready!
        
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