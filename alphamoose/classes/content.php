<?
/*
Class: Content
Status: Fresh
Functionality:
Comments: I am not doing content because it has a low priority;
I find this strange as we are nothing without content.  Alas....

*/
class Content{
	var $id;
	var $name;
	var $user_id;
	var $content;
	var $mime-type;
	var $content-type_id;
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
				$this->content-type_id = $data['content-type_id'];
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
	
	function upload($name_in, $start_in, $end_in, $feeds_in, $content_in, $content_type_in, $user_id_in){
		if($set == true){
			return 0;
		} else{
			$
	
	}


?>