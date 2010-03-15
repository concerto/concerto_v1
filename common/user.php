<?
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technologies Group)
 *
 * This program is free software; you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.  You should have received a copy
 * of the GNU General Public License along with this program.
 *
 * @package      Concerto
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */
/*
Class: User
Status: Good to go
Functionality:  Create users, allows access to user properties, group stuff, etc
        create_user         		Creates a new user
        set_properties         	Writes user properties back to the database
        add_to_group	        	Adds current user to a group
        remove_from_group       	Removes the user from a group
        in_group			Tests to see if a user is in a group	
        list_groups			Lists all the groups a user is in			
        can_write			Tests to see if a user can write to an object (essentially a permission check)
	 send_mail			Sends the user an email, if they have that setting enabled.
	 controls_afeed		Tests to see if a user is in a group that owns any feeds.
	 controls_ascreen		Tests to see if a user is in a group that owns screens.
	 is_special			If controls_afeed or controls_ascreen.
   has_ndc_rights

Comments: 
	can_write is my basic implementation of 'privledges', essentially it combines owner + group to test if the user is an owner who can write
	Fast patch, users cannot leave information blank when creating accounts  
*/
class User{
	var $id;
	var $username;
	var $name;
	var $firstname;
	var $email;
	var $allow_email;
	var $admin_privileges;
	
	var $groups = array();
	var $set;
	var $status;
		
	function __construct($username_in = ''){
		$this->status = "";
		if($username_in != ''){
	        	if(is_numeric($username_in))
            			$sql = "SELECT * FROM user WHERE id = '$username_in' LIMIT 1";
         		else
            			$sql = "SELECT * FROM user WHERE username = '" . escape($username_in) . "' LIMIT 1";
			$res = sql_query($sql);
			if($res != 0){
				$data = (sql_row_keyed($res,0));
				$this->id = $data['id'];
				$this->username = $data['username'];
				$this->name = $data['name'];
				$this->email = $data['email'];
				$this->admin_privileges = $data['admin_privileges'];
				$this->allow_email = $data['allow_email'];

				//Get firstname for aesthetic output
				$namesplit = split(" ",$this->name);
				$this->firstname = $namesplit[0]; 

				//Find groups the user belongs to
				$sql1 = "SELECT group_id FROM user_group WHERE user_id = $this->id";
				$res1 = sql_query($sql1);
				if($res1 != 0){
					$i = 0;
					while($row = sql_row_keyed($res1, $i)){
						$this->groups[] = $row['group_id'];
						$i++;
					}
				}
				//End group block
				
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
	
	//Creates a user
	function create_user($username_in, $name_in, $email_in, $admin_privileges_in, $allow_email_in = 1, $password_in = ''){
		if($this->set == true){
			return false; //We already have a user object you idiot
		} else {
			//Cleaning Block
			if($username_in == "" || $name_in == "" || $email_in == "" || $password_in == ""){ //All user fields must be set!
				$this->status .= "The username, name, or email address was blank.  ";
				return false;
			}
			$password = md5($password_in);
			$username_in = escape($username_in);
			$name_in = escape($name_in);
			$valid_email = "^[a-z0-9,!#\$%&'\*\+/=\?\^_`\{\|}~-]+(\.[a-z0-9,!#\$%&'\*\+/=\?\^_`\{\|}~-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,})$";
			if(!eregi($valid_email, $email_in)){ //Test for a valid email address
				$this->status .= "The email address entered doesn't look valid.  ";
				return false;
			}
			$email_in = escape($email_in);
			if(!is_numeric($admin_privileges_in)){
				$admin_privileges_in = 0; //Fix any error by making them not an admin
			}
			if(!is_numeric($allow_email_in)){
				$allow_email_in = 1; //Fix any error by enabling email
			}

			//End testing/cleaning block
			
			$sql = "INSERT INTO user (username, password, name, email, admin_privileges, allow_email) VALUES ('$username_in', '$password', '$name_in', '$email_in', $admin_privileges_in, $allow_email_in)";
			
			$res = sql_query($sql);
			if($res){
				$sql_id = sql_insert_id();
				
				$this->id = $sql_id;
				$this->username = stripslashes($username_in);
				$this->name = stripslashes($name_in);
				$this->email = stripslashes($email_in);
				$this->admin_privileges = $admin_privileges_in;
				$this->allow_email = $allow_email_in;
				
				//Get firstname for aesthetic output
				$namesplit = split(" ",$this->name);
				$this->firstname = $namesplit[0]; 

				$this->set = true;
	
				$notify = new Notification();
	                        $notify->notify('user', $this->id, '', '', 'new');

				return true;
			} else {
				$this->status .= "A database error occured creating your account.  ";
				return false;
			}
			
		}
	
	}
	
	//Sets their properties back to the database
	function set_properties(){
		//Cleaning Block
                if($this->username == "" || $this->name == "" || $this->email == ""){ //All user fields must be set!
                        return false; 
                }
                $username_in = escape($this->username);
                $name_in = escape($this->name);
				$valid_email = "^[a-z0-9,!#\$%&'\*\+/=\?\^_`\{\|}~-]+(\.[a-z0-9,!#\$%&'\*\+/=\?\^_`\{\|}~-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,})$";
				if(!eregi($valid_email, $this->email)){ //Test for a valid email address
					return false;
				}
                $email_in = escape($this->email);
                if(!is_numeric($this->admin_privileges)){
                        return false;
                }
                if(!is_numeric($this->allow_email)){
                        return false;
                }

		$sql = "UPDATE user SET username = '$username_in', name = '$name_in', email = '$email_in', admin_privileges = '$this->admin_privileges', 
allow_email = '$this->allow_email' WHERE id = $this->id LIMIT 1";
		$res = sql_query($sql);
		if($res){
			$notify = new Notification();
                     $notify->notify('user', $this->id, 'user', $_SESSION['user']->id, 'update');

			return true;
		} else {
			return false;
		}
	
	}
	//Adds a person to a group
	function add_to_group($group_id){
		if(is_numeric($group_id) && !in_array($group_id, $this->groups)){
			$sql = "INSERT INTO user_group (user_id, group_id) VALUES ($this->id, $group_id)";
			$res = sql_query($sql);
			if($res != 0){
				$this->groups[] = $group_id;
	
				$notify = new Notification();
	                        $notify->notify('group', $group_id, 'user', $this->id, 'join');

				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	//Removes a person from a group
	function remove_from_group($group_id){
		if(in_array($group_id, $this->groups)){
			$sql = "DELETE FROM user_group WHERE user_id = $this->id AND group_id = $group_id LIMIT 1";
			$res = sql_query($sql);
			if($res != 0){
				$key = array_search($group_id, $this->groups);
				unset($this->groups[$key]);

				$notify = new Notification();
	                        $notify->notify('group', $group_id, 'user', $this->id, 'leave');

				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
	
	//Tests to see if a person is part of a group
	function in_group($group_id){
		if(in_array($group_id, $this->groups)){
			return true;
		} else {
			return false;
		}
	}
	
	//Lists all the groups a user is in
        function list_groups(){
                $data = array();
		foreach($this->groups as $group_id){
			$data[] = new Group($group_id);
		}
		return $data;
	}
	
	//Checks if a user should have access to write/modify an existing object
	function can_write($type, $item_id){
		//The admin override
		if($this->admin_privileges){
			return true;
		}
		$item_id = escape($item_id);
		//Feed Test
		if($type == 'feed'){
			$sql = "SELECT group_id FROM feed WHERE id = $item_id";
			$res = sql_query($sql);
			if($res != 0){
				$data = (sql_row_keyed($res,0));
				$group_id = $data['group_id'];
				return $this->in_group($group_id);
			} else {
				return false;
			}
		//Content Test
		} else if($type == 'content'){
			$sql = "SELECT user_id FROM content WHERE id = $item_id";
			$res = sql_query($sql);
			if($res != 0){
				$data = (sql_row_keyed($res,0));
				$user_id = $data['user_id'];
				if($this->id == $user_id){
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		//Screen Test
		} else if($type == 'screen'){
			$sql = "SELECT group_id FROM screen WHERE id = $item_id";
			$res = sql_query($sql);
			if($res != 0){
				$data = (sql_row_keyed($res,0));
				$group_id = $data['group_id'];
				return $this->in_group($group_id);
			} else {
				return false;
			}
		} else if($type == 'group'){
      		      return $this->in_group($item_id);
      } else if($type == 'user'){
         return ($this->username == $item_id) || ($this->id == $item_id);
      }

	}
	function send_mail($subject, $msg_in, $from='', $forward = false){
		if($this->allow_email){
			$to = "$this->name <$this->email>";
			if($from == ''){
				$from = SYSTEM_EMAIL;
			}

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-Type: text/plain; charset="UTF-8"' . "\r\n";
			$headers .= "From: $from\r\n";
  			$headers .= "Reply-To: $from\r\n";
  			$headers .= 'X-Mailer: Concerto';
			
			$msg = "";
			if(!$forward){
				$msg .= "Hi $this->firstname,\r\n"; //Greet the user
			}

			$msg .= $msg_in;

			if(!$forward){
				$msg .= "\r\n\r\nThanks,\r\nThe Concerto Team\r\n\r\n"; //Prepend some footer content
			}
			$msg .= "___\r\n";
			$msg .= "Want to control which emails you receive from Concerto? Go to:\r\n";
			$msg .= "http://" . $_SERVER['SERVER_NAME'] . ADMIN_URL . "/users/edit/$this->username";
			
			return mail($to, $subject, $msg, $headers);
		} else {
			return true;  //We return true because the user didn't want to get email, this isn't something we should penalize them for
		}	
	}
	function controls_afeed(){
		if($this->set){
			$groups = implode(',',$this->groups);
			$sql = "SELECT COUNT(id) AS f_count FROM feed WHERE group_id IN ($groups)";
			$res = sql_query($sql);
			if(($res && $data = sql_row_keyed($res,0)) && $data['f_count'] > 0){
				return true;
			} else {
				return false;
			}
		}
	}
	function controls_ascreen(){
		if($this->set){
			$groups = implode(',',$this->groups);
			$sql = "SELECT COUNT(id) AS s_count FROM screen WHERE group_id IN ($groups)";
			$res = sql_query($sql);
			if(($res && $data = sql_row_keyed($res,0)) && $data['s_count'] > 0){
				return true;
			} else {
				return false;
			}
		}
	}
	function is_special(){
		if($this->controls_afeed() || $this->controls_ascreen()){
			return true;
		} else {
			return false;
		}
	}
  function has_ndc_rights(){
    if($this->set){
			$groups = implode(',',$this->groups);
			$sql = "SELECT COUNT(id) AS f_count FROM feed WHERE type = 4 AND group_id IN ($groups)";
			$res = sql_query($sql);
			if(($res && $data = sql_row_keyed($res,0)) && $data['f_count'] > 0){
				return true;
			} else {
				return false;
			}
		}
  }
  function auth_test($username_in, $password){
    $password = md5($password);
    $username = escape($username_in);
    $sql = "SELECT COUNT(id) as id_count FROM user WHERE username = '$username' AND password = '$password'";
                                
    $res = sql_query($sql);
    if(($res && $data = sql_row_keyed($res,0)) && $data['id_count'] == 1){
      return true;
    } else {
      return false;
    }
  }
  function change_password($curpass_in, $newpass_in){
    if(!$this->set){
      return false;
    }
    $password = md5($curpass_in);
    $sql = "SELECT COUNT(id) as id_count FROM user WHERE id = $this->id AND password = '$password'";
    $res = sql_query($sql);
    if(($res && $data = sql_row_keyed($res,0)) && $data['id_count'] == 1){
      //We're ok to update the password
      $password = md5($newpass_in);
      $sql = "UPDATE user SET password = '$password' WHERE id = $this->id LIMIT 1";
      sql_query($sql);
      return true;
    } else {
      return false;
    }
  }
}
?>
