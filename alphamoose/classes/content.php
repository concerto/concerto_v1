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
Comments: More progress made...
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
			$sql = "INSERT INTO content 
			(name, user_id, content, mime_type, type_id, duration, start_time, end_time, submitted)
			VALUES
			('$name_in', $user_id_in, '$content_in', '$mime_type_in', $type_id_in, $duration_in, '$start_time_in', '$end_time_in', NOW())";
			$res = sql_query($sql);
            		if($res){
                		$sql_id = sql_insert_id();

                		$this->id = $sql_id;
                		$this->name = $name_in;
                		$this->content = $content_in;
                		$this->mime_type = $mime_type_in;
                		$this->type_id = $type_id_in;
                		$this->duration = $duration_in;
                		$this->start_time = $start_time_in;
                		$this->end_time = $end_time_in;
                		$this->submitted = date("Y:m:d H:i:s", time());
				
                		$this->set = true;
                		return true;
           		} else {
                		return false;
            		}
		}
	}
	//Sets properties back to database, will NOT moderate content or change some constant values
	function set_properties(){
		$sql = "UPDATE content SET name = '$this->name', content = '$this->content', duration = '$this->duration', start_time = '$this->start_time', end_time = '$this->end_time' WHERE id = $this->id LIMIT 1";
		$res = sql_query($sql);
        	if($res){
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
		if($this->mime_type != 'text/plain' && $this->mime_type != 'text/html'){
			$path = CONTENT_STORE . $this->content;
			echo $path;
			unlink($path);
		}
		if(!$return){
			return false;  //Failure to delete content
		}
		$sql = "DELETE FROM content WHERE id = $this->id";
		$res = sql_query($sql);
		if($res){
			return true;
		} else {
			return false;
		}
	}
}	
?>
