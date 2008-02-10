<?
/*
Class: Screen
Status: Done maybe?
Functionality:
	set_properties		Writes all data back the the screen table
	
	add_position		Creates a "position" mapping a feed_id and 
					a field_id for that screen ensuring the link
					does not already exist
						
	remove_position		Removes a "position" based on its identifier.
					Performs basic check to ensure the identifier
					belongs to this screen
						
	list_positions		Rerutns an array of all the joins that occur
					between fields and feeds for said screen.  
					Optionally takes a field id and lists the feeds
					for only that field						
	
	avail_positions		Returns an array of all potential joins that can
					occur, essentially the inverse of list_position.
					Optionally takes a field id and lists the feeds
					for only that field	
	
	list_fields			Lists all fields a screen has, based on template
	
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
	 
	 //The default constructor takes a screen ID and pulls all of the data out for quick and easy access
	 function __construct($macid){
	 	//Returns true for sucess, false for failure
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
			return true;
		} else {
			return false; //Unable to find a screen
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
	
	//Adds a new map between a feed ID and a field ID,
	//making sure the user, potentially and idiot,
	//doesn't join the same field twice.
	function add_position($feed_id, $field_id){
		//Returns true for sucess, false for failure
		$sql = "SELECT id FROM position WHERE screen_id = $this->id AND feed_id = $feed_id AND field_id = $field_id";
		if(sql_count(sql_query($sql)) > 0){
			return false; //Someone can't read
		} else {
			$sql1 = "INSERT INTO position (screen_id, feed_id, field_id) VALUES ($this->id, $feed_id, $field_id)";
			$res = sql_query($sql1);
                	if($res != 0){
                        	return true;
                	} else {
                        	return false;
                	}

		}
	}
	
	//Pretty simple, blow away the row with position_id as its identifier
	//Just for fun we'll ensure its the correct screen
	function remove_position($position_id){
		//Returns true for sucess, false for failure
		$sql = "DELETE FROM position WHERE id = $position_id AND screen_id = $this->id";
		$res = sql_query($sql);
                if($res != 0){
                        return true;
                } else {
                        return false;
                }
	}
	
	//Finds all the positions and returns a pretty array sorted by field, then by feed
	function list_positions($field_id = ''){
		//Returns a 2d Array of join ids, field ids, and feed ids to be displayed however we want
		$sql_insert = "";
		if($field_id != ''){
			$sql_insert = "AND field_id = $field_id";
		}
			
		$sql = "SELECT id, field_id, feed_id FROM position WHERE screen_id = $this->id $sql_insert ORDER BY field_id ASC, feed_id ASC";
		
		$res = sql_query($sql);
		$i = 0;
		while($row = sql_row_keyed($res,$i)){
		    $data[$i]['id'] = $row['id'];
			$data[$i]['field_id'] = $row['field_id'];
			$data[$i]['feed_id'] = $row['feed_id'];
		    $i++;
		}
		return $data;
	}
	//Finds the positions that can be mapped, kinda the opposite of list_functions
	function avail_positions($field_in = ''){
		//Returns a 2d Array of field id and feed ids that they can be joined to
		if($field_in != ''){
			$fields[] = $field_in;
		} else{
			$fields = $this->list_fields();
		}
		$i = 0;
		while($field_id = $fields[$i]){
			$sql2 = "SELECT id FROM feed WHERE id NOT IN (SELECT feed_id FROM position WHERE field_id = $field_id AND screen_id = $this->id) ORDER BY id ASC";
			$res2 = sql_query($sql2);
			$j = 0;
			while($feed_row = sql_row_keyed($res2, $j)){
				$feed_id = $feed_row['id'];
				$data[$count]['field_id'] = $field_id;
				$data[$count]['feed_id'] = $feed_id;
				$j++;
				$count++;
			}
			$i++;
		}
		return $data;
	}
	
	//Lists all the fields that a screen has, based on its template
	function list_fields(){
		//Returns an array of fields
		$sql = "SELECT id FROM field WHERE template_id = $this->template_id ORDER BY id ASC";
		$res = sql_query($sql);
		$i = 0;
		while($field_row = sql_row_keyed($res, $i)){
			$data[$i] = $field_row['id'];
			//$data[$i]  = new Field($field_row['id'], $this->id); //V2
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
