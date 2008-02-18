<?
/*
Class: Group
Status: n00b
Functionality: 
       create_group       		Creates a new group
	   
        get_members			Lists all the members in a group
Comments: 
  
*/
class Group{
	var $id;
	var $name;
	
	var $set;
	
	function __construct($id_in = ''){
		if($id_in != ''){
			$sql = "SELECT * FROM `group` WHERE id = $id_in LIMIT 1";
			$res = sql_query($sql);
			if($res != 0){
				$data = (sql_row_keyed($res,0));
				$this->id = $data['id'];
				$this->name = $data['name'];
				
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
	
	function create_group($name_in){
		if($this->set == true){
			return false; //Someone isn't wearing a seatbelt
		} else {
			$sql = "INSERT INTO `group` (name) VALUES ('$name_in')";
			$res = sql_query($sql);
			if($res){
				$sql_id = sql_insert_id();
				
				$this->id = $sql_id;
				$this->name = $name_in;
				
				$this->set = true;
				return true;
			} else {
				return false;
			}
		}
	}
	
	function get_members(){
		$sql = "SELECT user_id FROM user_group WHERE group_id = $this->id";
		$res = sql_query($sql);
		$i = 0;
		while($row = sql_row_keyed($res,$i)){
			$user_id = $row['user_id'];
			$sql2 = "SELECT username FROM user WHERE id = $user_id LIMIT 1";
			$res2 = sql_query($sql2);
			$user_row = sql_row_keyed($res2,0);
			$username = $user_row['username'];
			$data[] = new User($username);
			$i++;
		}
		return $data;	
	}
}
?>