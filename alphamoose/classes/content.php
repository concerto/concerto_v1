<?
/*
Class: Content
Status: In Progress
Functionality:
Comments: Starting to get some work
*/
class Content{
	var $id;
	var $name;
	var $user_id;
	var $content;
	var $mime-type;
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
				$this->mime-type = $data['mime-type'];
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
	
	function create_content($name_in, $user_id_in, $content_in, $mime-type_in, $type_id_in, $duration_in, $start_time_in, $end_time_in){
		if($this->set == true){
			return false;
		} else {
			$sql = "INSERT INTO content 
			(name, user_id, content, mime-type, type_id, duration, start_time, end_time, duration)
			VALUES
			($name_in, $user_id_in, $content_in, $mime-type_in, $type_id_in, $duration_in, $start_time_in, $end_time_in, NOW())";
			$res = sql_query($sql);
            if($res){
                $sql_id = sql_insert_id();

                $this->id = $sql_id;
                $this->name = $name_in;
                $this->content = $content_in;
                $this->mime-type = $mime-type_in;
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
	function set_properties(){
		$sql = "UPDATE content SET name = '$this->name', duration = '$this->duration', start_time = '$this->start_time', end_time = '$this->end_time' WHERE id = $this->id LIMIT 1";
		$res = sql_query($sql);
            if($res){
                return true;
            } else {
                return false;
            }
	
	}
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
	function list_feeds(){
		$sql = "SELECT feed_id, moderation_flag FROM feed_content WHERE content_id = $this->id";
		$res = sql_query($sql);
		$i = 0;
		while($row = sql_row_keyed($res,$i)){
			$data[$i]['feed_id'] = $row['feed_id'];
			$data[$i]['moderation_flag'] = $row['moderation_flag'];
		    $i++;
		}
		return $data;
	}
	function avail_feeds(){
		$sql2 = "SELECT * FROM feed WHERE id NOT IN (SELECT feed_id FROM feed_content WHERE content_id = $this->id) ORDER BY id ASC";
		$res = sql_query($sql2);
		$i=0;
		while($row = sql_row_keyed($res, $i){
			$data[$i]['id'] = $row['id'];
			$data[$i']['name'] = $row['name'];
			$i++;
		}
		return $data
	}
	
?>
