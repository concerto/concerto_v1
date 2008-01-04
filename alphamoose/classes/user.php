<?
/*
Class: User
Status: New
Functionality:
Comments: 

*/
class User{
	var $id;
	var $username;
	var $name;
	var $email;
	var $admin_privileges;
	
	var $set;
		
	function __construct($userid = ''){
		if($userid != ''){
			$sql = "SELECT * FROM user WHERE id = $userid LIMIT 1";
			$res = sql_query($sql);
			if($res != 0){
				$data = (sql_row_keyed($res,0));
				$this->id = $data['id'];
				$this->username = $data['username'];
				$this->name = $data['name'];
				$this->email = $data['email'];
				$this->admin_privileges = $data['admin_privileges'];
				print_r($data);
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
	
	function create_user($username_in, $name_in, $email_in, $admin_privileges_in){
		if($set == true){
			return false; //We already have a user object you idiot
		} else {
			$sql = "INSERT INTO user (username, name, email, admin_privileges) VALUES ($username_in,$name_in, $email_in, $admin_privileges_in)";
			$res = sql_query($sql);
			if($res){
				$sql_id = sql_insert_id();
				
				$this->id = $sql_id;
				$this->username = $username_in;
				$this->name = $name_in;
				$this->email = $email_in;
				$this->admin_privileges = $admin_privileges_in;
				$this->set = true;
				
				return true;
			} else {
				return false;
			}
			
		}
	
	}
	
	function set_properties(){
		$sql = "UPDATE user SET username = '$this->username', name = '$this->name', email = '$this->email', admin_privileges = '$this->admin_privileges' WHERE id = $this->id LIMIT 1";
		$res = sql_query($sql);
		if($res != 0){
			return true;
		} else {
			return false;
		}
	
	}



}
?>