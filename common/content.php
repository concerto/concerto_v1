<?
/*
Class: Content
Status:Working
Functionality:
	create_content		Builds a content, designed to take stuff from an uploader
	set_properties		Writes changes back to the table
	is_live			Tests to see if a content is live or not
	list_feeds			Returns an array of feed objects, as well as the moderation status
	avail_feeds			Returns an array of feeds the content can be joined to
	destroy			Deletes the content from the system, and all feeds its in.
Comments:
Now will render date (mime_type = test/time)
Cleaned
*/
class Content{
	var $id;
	var $name;
	var $user_id;
	var $content;
	var $mime_type;
	var $type_id;
	var $duration;
	var $start_time;
	var $end_time;
	var $submitted;
	
	var $status;
	var $set;
	
	function __construct($contentid = ''){
		if($contentid != ''){
			$sql = "SELECT * FROM content WHERE id = $contentid LIMIT 1";
			$res = sql_query($sql);
			if($res != 0){
				$data = (sql_row_keyed($res,0));
				$this->id = $data['id'];
				$this->name = $data['name'];
				$this->user_id = $data['user_id'];
				$this->content = $data['content'];
				$this->mime_type = $data['mime_type'];
				if($this->mime_type == 'text/time'){ //Patch to render time.
					$this->content = date($this->content);
				}
				$this->type_id = $data['type_id'];
				$this->duration = $data['duration'];
				$this->start_time = $data['start_time'];
				$this->end_time = $data['end_time'];
				$this->submitted = $data['submitted'];

				$this->set = true;
				return 1;
			} else {
				return 0;
			}		
		} else {
			$this->set = false;
			return 1;
		}
	}
	//Creates content, assumes it has already been handled by uploader
	function create_content($name_in, $user_id_in, $content_in, $mime_type_in, $type_id_in, $duration_in, $start_time_in, $end_time_in){
		if($this->set == true){
			return false;
		} else {
			//Begin testing/cleaning block
			$name_in = escape($name_in);
			$content_in = escape($content_in);
			$mime_type_in = escape($mime_type_in);
			if(!is_numeric($duration_in)){
				$this->status = "Duration should be a number";
				return false;
			}
			if(!($start_time_in=strtotime($start_time_in))){
				$this->status = "Unable to understand start time";
				return false;
			}
			$start_time_in = date("Y-m-d G:i:s", $start_time_in);
			if(!($end_time_in=strtotime($end_time_in))){
				$this->status = "Unable to understand end time";
				return false;
			}
			$end_time_in = date("Y-m-d G:i:s", $end_time_in);
			if(!is_numeric($user_id_in) || !is_numeric($type_id_in)){
				$this->status = "Unknown Error"; //Aka they are playing with the post data!
				return false;
			}
			//End testing/cleaning block
			
			$sql = "INSERT INTO content 
			(name, user_id, content, mime_type, type_id, duration, start_time, end_time, submitted)
			VALUES
			('$name_in', $user_id_in, '$content_in', '$mime_type_in', $type_id_in, $duration_in, '$start_time_in', '$end_time_in', NOW())";
			$res = sql_query($sql);
			//echo $sql;
            	if($res){
                	$sql_id = sql_insert_id();

                	$this->id = $sql_id;
                	$this->name = stripslashes($name_in); //Since we aren't pulling them back via mysql, they will be escaped
                	$this->user_id = $user_id_in;
			$this->content = stripslashes($content_in);
                	$this->mime_type = $mime_type_in;
                	$this->type_id = $type_id_in;
                	$this->duration = $duration_in;
                	$this->start_time = $start_time_in;
                	$this->end_time = $end_time_in;
                	$this->submitted = date("Y:m:d H:i:s", time());
				
                	$this->set = true;

                	if($this->user_id != 0){ //Avoid logging dynamic content
				$notify = new Notification();
				$notify->notify('content', $this->id, 'user', $this->user_id, 'new');
			}

			return true;
           		} else {
                	return false;
            	}
		}
	}
	//Sets properties back to database, will NOT moderate content or change some constant values
	function set_properties(){
		//Cleaning Block
		$name_clean = escape($this->name);
		$content_clean = escape($this->content);
		if(!is_numeric($this->duration)){
			$this->status = "Duration should be a number";
			return false;
		}
		if(!($this->start_time=strtotime($this->start_time))){
			$this->status = "Unable to understand start time";
			return false;
		}
		$this->start_time = date("Y-m-d G:i:s", $this->start_time);
		if(!($this->end_time=strtotime($this->end_time))){
			$this->status = "Unable to understand end time";
			return false;
		}
			$this->end_time = date("Y-m-d G:i:s", $this->end_time);
			//End testing/cleaning block
			
		$sql = "UPDATE content SET name = '$name_clean', content = '$content_clean', duration = '$this->duration', start_time = '$this->start_time', end_time = '$this->end_time' WHERE id = $this->id LIMIT 1";
		$res = sql_query($sql);
        	if($res){
			$notify = new Notification();
                        $notify->notify('content', $this->id, 'user', $_SESSION['user']->id, 'update');
			return true;
        	} else {
            		return false;
        	}
	}
	//Checks to  see if a content is live based on date, for a per feed check use list_feeds
	function is_live(){
		$start = strtotime($this->start_date);
		$end = strtotime($this->end_date);
		$now = time();
		if($start < $now && $now < $end){
			return true;
		} else {
			return false;
		}
	}
	//Lists all feeds a content has been submitted to, as well as their moderation status
	function list_feeds(){
		$sql = "SELECT feed_id, moderation_flag FROM feed_content WHERE content_id = $this->id";
		$res = sql_query($sql);
		$i = 0;
		while($row = sql_row_keyed($res,$i)){
			$data[$i]['feed'] = new Feed($row['feed_id']);
			$data[$i]['moderation_flag'] = $row['moderation_flag'];
		    	$i++;
		}
		if(isset($data)){
			return $data;
		} else {
			return false;
		}
	}
	//Lists all feeds content has not been submitted to
	function avail_feeds(){
		$sql2 = "SELECT id FROM feed WHERE id NOT IN (SELECT feed_id FROM feed_content WHERE content_id = $this->id) ORDER BY id ASC";
		$res = sql_query($sql2);
		$i=0;
		while($row = sql_row_keyed($res, $i)){
			$data[$i] = new Feed($row['id']);
			$i++;
		}
		return $data;
	}
	function destroy(){
		$return = true;
		if($data = $this->list_feeds()){ // To get all the feeds the content is in
			foreach($data as $feed_row){
				$return = $return * $feed_row['feed']->content_remove($this->id);
			}
		}
		if(!$return){
			return false; //Failure to remove the content from all the feeds it was in
		}
		if($this->mime_type != 'text/plain' && $this->mime_type != 'text/html' && $this->mime_type != 'text/time'){
			$path = IMAGE_DIR . $this->content;
			if(unlink($path)){
				$this->status = "File deleted.";
			}else{
				$this->status  = "Trouble finding content, maybe you already removed it?";
			}
		}
		if(!$return){
			return false;  //Failure to delete content
		}
		$sql = "DELETE FROM content WHERE id = $this->id";
		$res = sql_query($sql);
		if($res){
			if($this->user_id != 0){
				$notify = new Notification();
                        	$notify->notify('content', $this->id, 'user', $_SESSION['user']->id, 'delete');
			}
			return true;
		} else {
			return false;
		}
	}
}	
?>
