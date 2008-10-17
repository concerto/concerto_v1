<?
/*
Class: Screen
Status: Done with powerstate
Functionality:
	set_properties		Writes all data back the the screen table
	add_position		[[Depreciated]]  use field -> position link.						
	remove_position		[[Depreciated]]				
	list_positions		[[Depreciated]]					
	avail_positions		[[Depreciated]]
	list_fields		Lists all fields objects a screen has, based on template
	status_update		Sets the screen update time to now, should be called
				whenever that screen refreshes content		
	status			Queries the status of a screen, returning the last time
				the screen was updated.  Passing it a 0 will return a raw
				sql timestamp, and a 1 [default] will return a pretty string.		
	list_all		Lists all screens, optional where syntax
	get_all			same, returns objects instead of array
	get_powerstate		Tells us about the powerstate, should it be on or off
	destroy			destroys a screen
Comments:
The powerstate function is a copy and paste of the logic in the screen controller with a few slight mods.
*/
class Screen{
     var $id;
     var $name;
     var $group_id;
     var $location;
     var $mac_address; //Ignore me
     var $mac_inhex; //Use this
     var $width;
     var $height;
     var $template_id;
     
     var $last_updated; //read-only
     var $last_ip;      //read-only

     var $controls_display;
     var $time_on;
     var $time_off;
	 
     var $set;
	 
	 //The default constructor takes a screen ID and pulls all of the data out for quick and easy access
    //Note: This now takes the database ID, rather than the mac address.
	 function __construct($sid = '', $id_is_mac=false){
       if($id_is_mac) {
          if(preg_match('/^[a-fA-F0-9:]*$/',$sid)) {
             $mac_hex_in = eregi_replace("[\s|:]", '', $sid);
             $mac_address_in = hexdec($mac_hex_in);
             $sql = "SELECT *, HEX(mac_address) as inhex from screen WHERE mac_address = '$mac_address_in' LIMIT 1";
          }
       } else {
          if(is_numeric($sid)) {
             $sql = "SELECT *, HEX(mac_address) as inhex from screen WHERE id = $sid LIMIT 1";
          }
       }

		if(isset($sql)){
			$res = sql_query($sql);
			$data = (sql_row_keyed($res,0));
			if($data != 0){
				$this->id = $data['id'];
				$this->name = $data['name'];
				$this->group_id = $data['group_id'];
				$this->location = $data['location'];
				$this->mac_address = $data['mac_address']; //Do not touch, your changes will not be saved.
				
				$this->width = $data['width'];
				$this->height = $data['height'];
				$this->template_id = $data['template_id'];
				$this->last_updated = $data['last_updated'];
				$this->last_ip = $data['last_ip'];

				
				$this->controls_display = $data['controls_display'];
				$this->time_on = $data['time_on'];
				$this->time_off = $data['time_off'];

				//This is done by sql because a mac is bigger than php's int.
				$this->mac_inhex = $data['inhex']; //You want to update this field only!
				
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
	
	function create_screen($name_in, $group_id_in, $location_in, $mac_hex_in, $width_in='', $height_in='', $template_id_in=''){
		if($this->set){
			return false;
		} else {
			//Begin testing/cleaning block
			$name_in = escape($name_in);

			$location_in = escape($location_in);
			
			$mac_hex_in = eregi_replace("[\s|:]", '', $mac_hex_in);
			$mac_address_in = hexdec($mac_hex_in);
			
			if(!is_numeric($group_id_in) || !is_numeric($width_in) || !is_numeric($height_in) || !is_numeric($template_id_in)){
				return false;
			}
			//End testing/cleaning block
			$sql = "INSERT INTO `screen` (name, group_id, location, mac_address, width, height, template_id) VALUES ('$name_in', $group_id_in, '$location_in', '$mac_address_in', $width_in, $height_in, $template_id_in)";
                        //print $sql; die;
			$res = sql_query($sql);
			if($res){
				$sql_id = sql_insert_id();
				
				$this->id = $sql_id;
				$this->name = stripslashes($name_in);
				$this->group_id = $group_id_in;
				$this->location = stripslashes($location_in);
				$this->mac_address = $mac_address_in;
				$this->mac_inhex = $mac_hex_in;
				$this->width = $width_in;
				$this->height = $height_in;
				$this->template_id = $template_id_in;
				$this->last_updated = 0;
				
				$this->set = true;

				$notify = new Notification();
	                        $notify->notify('screen', $this->id, 'group', $this->group_id, 'new');

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
		
		//Begin Cleaning/Test Block
		$name_clean = escape($this->name);
		$location_clean = escape($this->location);
      		$time_on_clean = escape($this->time_on);
      		$time_off_clean = escape($this->time_off);
      		if($this->controls_display) $this->controls_display=1;
		if(!is_numeric($this->group_id)){
				return false;
		}
		if(!is_numeric($this->width)){
				return false;
		}
		if(!is_numeric($this->height)){
				return false;
		}
		if(!is_numeric($this->template_id)){
				return false;
		}
		$this->mac_inhex = eregi_replace('[\s|:]', '', $this->mac_inhex);
		//End Cleaning/Test Block
		
		if(hexdec($this->mac_inhex) != $this->mac_address){
			$this->mac_address = hexdec($this->mac_inhex);
		}
		
		$sql = "UPDATE screen SET name = '$name_clean',  group_id = '$this->group_id', location = '$location_clean', mac_address = '$this->mac_address', width = '$this->width', height = $this->height, template_id = $this->template_id, controls_display = $this->controls_display, time_on = '$time_on_clean', time_off = '$time_off_clean' WHERE id = $this->id LIMIT 1";
		//echo $sql;
		$res = sql_query($sql);
		if($res){
			$notify = new Notification();
                        $notify->notify('screen', $this->id, 'user', $_SESSION['user']->id, 'update');
	
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
	function status_update($ip_in=''){
    $ip = escape($ip_in);
    $now = date('Y-m-d G:i:s');
    $sql = "UPDATE screen SET last_updated = '$now', last_ip = '$ip' WHERE id = $this->id LIMIT 1";
    sql_query($sql);
    $this->last_updated = $now;
    $this->last_ip = $ip;
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
	
  function is_connected()
  {
    return (strtotime($this->last_updated)>strtotime('-30 seconds'));
  }
	
	// Returns an array that contains the number of screens online, offline, and asleep (in that order, with the first array element being the number of online screens).  The final element in the array is the total number of screens registered.
	function screenStats() {
		$numOnline = 0;
		$numOffline = 0;
		$numAsleep = 0;
		$total = 0;
		$sql = "SELECT * FROM screen";
		$res = sql_query($sql);
		$i = 0;
		while ($row = sql_row_keyed($res, $i)) {
			$temp = new Screen($row['id']);
			if ($temp->is_connected()&&$temp->get_powerstate()) {		// screen is ONLINE
				$numOnline++;
			} else if ($temp->is_connected()&&!$temp->get_powerstate()) {  // screen is ASLEEP
				$numAsleep++;
			} else {	// screen is OFFLINE
				$numOffline++;
			}
			$i++;
			$total = $numOnline + $numOffline + $numAsleep;
		}
		return array($numOnline, $numOffline, $numAsleep, $total);
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

	function get_all($where = ''){
		$sql = "SELECT id FROM screen $where";
		$res = sql_query($sql);
		$i=0;
		$found = false;
		while($row = sql_row_keyed($res,$i)){
			$found = true;
			$data[] = new Screen($row['id']);
			$i++;
		}
		if($found){
			return $data;
		} else {
			return false;
		}
	}
	function get_powerstate($h_in = -1, $m_in = -1) {
		if(!$this->controls_display){ //If we can't control the screen, it should always be on
			return true;
		}
		list($on_h,$on_m)=split(':',$this->time_on);
		list($off_h,$off_m)=split(':',$this->time_off);
  		$localtime = localtime();

  		$h = $localtime[2];
  		$m = $localtime[1];
  		$s = $localtime[0];

  		if($h_in > -1) $h=$h_in;
  		if($m_in > -1) $m=$m_in;

  		//Convert to seconds-based timestamps for comparisons
  		$on_ts = $on_h*3600+$on_m*60;
  		$off_ts = $off_h*3600+$off_m*60;
  		$ts=$h*3600+$m*60+$s;

  		//aon means the on time has passed already today.  aoff means it is later than the off time
  		$aon = $ts > $on_ts;
  		$aoff = $ts > $off_ts;
  		$reverse = $on_ts > $off_ts;

  		$pwrstatus = ($aon xor $aoff);
  		if($reverse) $pwrstatus = !$pwrstatus;
  
 	 	return $pwrstatus;
	}
	function destroy(){
		$sql = "DELETE FROM `position` WHERE screen_id = $this->id";
		$res = sql_query($sql);
		if(!$res){
			return false;
		}
		
		$sql = "DELETE FROM `screen` WHERE id = $this->id LIMIT 1";
		$res = sql_query($sql);
		if(!$res){
			return false;
		}
		$notify = new Notification();
                $notify->notify('screen', $this->id, 'user', $_SESSION['user']->id, 'delete');

		return true;
	}
}
?>
