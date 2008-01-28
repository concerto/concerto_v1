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
				$this->mime-type = $mime-type_inl
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


?>