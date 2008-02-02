<?
/*
Class: Position
Status: Done.  You can't write a class that fast can you?
Functionality:
	create_position		Creates a new position
	set_properties		Updates the range for a position, thats all
Comments: 
	Hasn't been tested, but looks cool

*/

class Position{
	var $id;
	var $screenid;
	var $feed_id;
	var $field_id;
	var $range_l;
	var $range_h;
	
	vat $set;
	
	function __construct($id_in = ''){
		if($id_in != ''){
			$sql = "SELECT * FROM position WHERE id = $id_in LIMIT 1";
			$res = sql_query($sql);
			if($res != 0){
				$data = (sql_row_keyed($res,0));
				$this->id = $data['id'];
				$this->screen_id = $data['screen_id'];
				$this->feed_id = $data['feed_id'];
				$this->fiend_id = $data['field_id'];
				$this->range_l = $data['range_l'];
				$this->range_h = $data['range_h'];
			
				$this->set = true;
				return true;
			} else {
				return false;
			}
		} else {
			$this->set = false;
			return true;
		}
	}

	//Creates a posotion, yea..
	function create_position($screen_id_in, $feed_id_in, $field_in_id, $range_l_in = 0, $range_h_in = 0){
		if($this->set){
			return true; //You've already got an object!
		} else {
			$sql = "SELECT COUNT(id) FROM position WHERE screen_id = $screen_id_in AND feed_id = $feed_id_in AND field_id = $field_id_in";
			$res = sql_query($sql);
			$data = (sql_row_keyed($res,0));
			if( $data['COUNT(id)'] != 0){
				return false;  //Implying the mapping already exists
			} else {
				$sql = "INSERT INTO position (screen_id, feed_id, field_id, range_l, range_h) VALUES ($screen_id_in, $feed_id_in, $field_id_in, $range_l_in, $range_h_in)";
				$res = sql_query($sql);
				if($res){
					$sql_id = sql_insert_id();

					$this->id = $sql_id;
					$this->screen_id = $screen_id_in;
					$this->feed_id = $feed_id_in;
					$this->field_id = $field_id_in;
					$this->range_l= $range_l_in;
					$this->range_h = $range_h_in;
				
					$this->set = true;
					return true;
				} else {
					return false;
			}
        }
	
	}
	
	//Updates ranges ONLY!
	function set_properties(){
		$sql = "UPDATE position SET range_l = '$this->range_l', range_h = '$this->range_h' WHERE id = $this->id LIMIT 1";
		$res = sql_query($sql);
		if($res != 0){
			return true;
		} else {
			return false;
		}
	
	}
}

?>