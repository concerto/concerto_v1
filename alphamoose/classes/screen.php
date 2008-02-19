<?
/*
Class: Screen
Status: Done maybe?
Functionality:
	create_screen		Makes a screen, incase you want a new one
	set_properties		Writes all data back the the screen table
	
	add_position		[[Depreciated]]  use field -> position link.						
	remove_position		[[Depreciated]]				
	list_positions		[[Depreciated]]					
	avail_positions		[[Depreciated]]
	
	list_fields			Lists all fields objects a screen has, based on template
	
	status_update		Sets the screen update time to now, should be called
					whenever that screen refreshes content
						
	status			Queries the status of a screen, returning the last time
					the screen was updated.  Passing it a 0 will return a raw
					sql timestamp, and a 1 [default] will return a pretty string.
						
	list_all			Lists all screens, optional where syntax
						
Comments:

*/
class Screen{
     var $id;
	 var $name;
	 var $group_id;
	 var $location;
	 var $mac_address;
	 var $width;
	 var $height;
	 var $template_id;
	 var $last_updated;
	 
	 var $set;
	 
	 //The default constructor takes a screen ID and pulls all of the data out for quick and easy access
	 function __construct($macid = ''){
	 	//Returns true for sucess, false for failure
		if($madid != ''){
			$sql = "SELECT * from screen WHERE mac_address = $macid LIMIT 1";
			$res = sql_query($sql);
			if($res != 0){
				$data = (sql_row_keyed($res,0));
				$this->id = $data['id'];
				$this->name = $data['name'];
				$this->group_id = $data['group_id'];
				$this->location = $data['location'];
				$this->mac_address = $data['mac_address'];
				$this->width = $data['width'];
				$this->height = $data['height'];
				$this->template_id = $data['template_id'];
				$this->last_updated = $data['last_updated'];
				
				$this->set = true;
				return true;
			} else {
				return false; //Unable to find a screen
			}
		} else {
			$this->set = false;
			return true;
		}
	}
	
	function create_screen($name_in, $group_id_in, $location_in, $mac_address_in, $width_in='', $height_in='', $template_id_in=''){
		if($this->set){
			return false;
		} else {
			$sql = "INSERT INTO `screen` (name, group_id, location, mac_address, width, height, template_id) VALUES ('$name_in', $group_id_in, '$location_in', '$mac_address_in', $width_in, $height_in, $template_id_in)";
			$res = sql_query($sql);
			if($res){
				$sql_id = sql_insert_id();
				
				$this->id = $sql_id;
				$this->name = $name_in;
				$this->group_id = $group_id_in;
				$this->location = $location_in;
				$this->mac_address = $mac_address_in;
				$this->width = $width_in;
				$this->height = $height_in;
				$this->template_id = $template_id_in;
				$this->last_updated = 0;
				
				$this->set = true;
				return true;	
			} else {
				return false;
			}
		}
	}
	
	//Sets the properties into the database that are currently stored in the class
	//You'll likely want to call this after you change 
	//anything if you expect those changes to stick around
	//YOU CANNOT USE THIS TO SET LAST_UPDATED for logical reasons I do not care to share
	function set_properties(){
		//Returns true for sucess, false for failure
		$sql = "UPDATE screen SET name = '$this->name',  group_id = '$this->group_id', location = '$this->location', mac_address = '$this->mac_address', width = '$this->width', height = '$this->height, template_id = $this->template_id' WHERE id = $this->id LIMIT 1";
		//echo $sql;
		$res = sql_query($sql);
		if($res != 0){
			return true;
		} else {
			return false;
		}
	}
	
	//Lists all the fields that a screen has, based on its template
	function list_fields(){
		//Returns an array of fields
		$sql = "SELECT id FROM field WHERE template_id = $this->template_id ORDER BY id ASC";
		$res = sql_query($sql);
		$i = 0;
		while($field_row = sql_row_keyed($res, $i)){
			$data[$i]  = new Field($field_row['id'], $this->id);
			$i++;
		}
		return $data;
	}
	
	//Updates the status of the screen
	function status_update(){
		$sql = "UPDATE screen SET last_updated = NOW() WHERE id = $this->id LIMIT 1";
		sql_query($sql);
		$this->last_updated = date("Y-m-d G:i:s");
	}
	
	//Gets the status of the screen
	function status($format = 1){
		if($format == 0){
			return $this->last_updated;	
		} else if($format == 1){
			$upstamp = strtotime($this->last_updated);
			$curstamp = strtotime("now");
			
			$retval = date("g:i a", $upstamp);
			
			$diffstamp = $curstamp - $upstamp;
            if($days=intval((floor($diffstamp/86400)))){
				$retval .= ", $days days ago";
			}
			return $retval;
		}
	}
	
	//List all screens, optional WHERE syntax
	function list_all($where = ''){
		$sql = "SELECT * FROM screen $where";
		$res = sql_query($sql);
		$i=0;
		while($row = sql_row_keyed($res,$i)){
		    $data[$i]['id'] = $row['id'];
			$data[$i]['name'] = $row['name'];
			$data[$i]['group_id'] = $row['group_id'];
			$data[$i]['location'] = $row['location'];
		    $i++;
		}
		return $data;
	}
}
?>
