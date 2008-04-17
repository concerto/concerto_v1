<?
/*
Class: Notification
Status: New
Functionality:  
Comments:		
*/
class Notification{
	var $id;
	var $timestamp;
	var $type;
	var $type_id;
	var $by;
	var $by_id;
	var $msg;
	
	var $text;
	var $type_obj;
	var $by_obj;
	
	var $set;
	function __construct($id = ''){
		if($id == ''){
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

				$this->set = true;
				$this->build();
				
			} else {
				$this->set = false;
			}
		}
	}
	
	//Log a notification in the table
	function notify($type_in, $type_id_in, $by_in, $by_id_in, $msg_in){
		$sql = "INSERT INTO `notifications` (`type`, `type_id`, `by_type`, `by_id`, `msg`, `processed`, `timestamp`) 
		VALUES ('$type_in', $type_id_in, '$by_in', $by_id_in, '$msg_in', 0, NOW())";
		$res = sql_query($sql);
	}
	
	//Builds the obj's from the types and ids for both type and by
	function build(){
		//Handles "type"
		if($this->type == 'feed'){
			$this->type_obj = new Feed($this->type_id);
		} elseif($this->type == 'content'){
			$this->type_obj = new Content($this->type_id);
		} elseif($this->type == 'group'){
			$this->type_obj = new Group($this->type_id);
		} elseif($this->type == 'screen'){
			$this->type_obj = new Screen($this->type_id);
		} elseif($this->type == 'user'){
			$this->type_obj = new User($this->type_id);
		}
		
		//Handles "by"
		if($this->by == 'feed'){
			$this->by_obj = new Feed($this->by_id);
		} elseif($this->by == 'content'){
			$this->by_obj = new Content($this->by_id);
		} elseif($this->by == 'group'){
			$this->by_obj = new Group($this->by_id);
		} elseif($this->by == 'screen'){
			$this->by_obj = new Screen($this->by_id);
		} elseif($this->by == 'user'){
			$this->by_obj = new User($this->by_id);
		}
		
		//Populates the text with something
		$text['feed']['content']['add'] = "%2 has been submitted to the %1 feed";
		$text['feed']['content']['approve'] = "%2 has been approved on %1 feed";
		$text['feed']['content']['deny'] = "%2 has been denied on %1 feed";
		$text['feed']['user']['update'] = "%1 feed has been updated by %2";
		$text['group']['user']['join'] = "%2 has joined the %1 group";
		$text['screen']['user']['update'] = "%2 has updated the %1 screen";
		
		if($temp_text = $text[$this->type][$this->by][$this->msg]){
			$temp_text = str_replace('%1', $this->type_obj->name, $temp_text);
			$temp_text = str_replace('%2', $this->by_obj->name, $temp_text);
			$this->text = $temp_text;
		}
	}
	function mark_sent(){
		if($this->set){
			$sql = "UPDATE notifications SET processed = 1 WHERE id = $this->id";
			sql_query($sql);
			return true;
		}
		return true;
	}
	function send_notifs($user_id){
		$usr = new User($user_id);
		$u_groups = implode(',', $usr->groups);
		$notifs = array();
		
		//This will fetch all "content needs approval" notifications
		$sql1 = "SELECT notifications.id, feed_content.moderation_flag, feed_content.content_id FROM notifications
				INNER JOIN feed ON notifications.type_id = feed.id
				INNER JOIN feed_content ON (feed.id = feed_content.feed_id AND notifications.by_id = feed_content.content_id)
				WHERE notifications.`type` = 'feed' AND notifications.`msg` = 'add' AND feed.group_id IN ($u_groups) 
				AND notifications.processed = 0  AND feed_content.`moderation_flag` IS NULL";
		$res1 = sql_query($sql1);
		if($res1 != 0){
			$i = 0;
			while($row = sql_row_keyed($res1, $i)){
				$notifs[] = new Notification($row['id']);
				$i++;
			}
		}
		/*
		//Select all "your content has been denied/approved
		$sql2 = "SELECT notifications.id FROM notifications
				INNER JOIN content ON notifications.by_id = content.id
				WHERE notifications.`type` = 'feed' AND notifications.`by_type` = 'content' 
				AND content.user_id = 1 AND notifications.processed = 0";
		*/
		
		$msg = "The following actions have items may require your attention:\n\n";
		foreach($notifs as $notif){
			$msg .= $notif->text . "\n";
			$notif->mark_sent();
		}
		$msg .= "\nYou can login to processs these actions here: http://signage.union.rpi.edu/admin/ \n\n";
		$usr->send_mail("Concerto Notification", $msg);
	}	
}
?>
