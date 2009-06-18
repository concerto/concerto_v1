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
Class: Position
Status: Done.  
Functionality:
	create_position		Creates a new position
	set_properties		Updates the range for a position, thats all
	delete_me			[[Depreciated]] Use destroy
	destroy			Removes a position
Comments: 
	Tested and working.
	Do not play with range_l and range_h unless you know what you're doing.  You're better off using rebalancer in field

*/

class Position{
	var $id;
	var $screen_id;
	var $feed_id;
	var $field_id;
	var $weight;
	
	var $set;
	
	function __construct($id_in = ''){
		if($id_in != '' && is_numeric($id_in)){
			$sql = "SELECT * FROM `position` WHERE id = $id_in LIMIT 1";
			$res = sql_query($sql);
			if($res != 0){
				$data = (sql_row_keyed($res,0));
				$this->id = $data['id'];
				$this->screen_id = $data['screen_id'];
				$this->feed_id = $data['feed_id'];
				$this->field_id = $data['field_id'];
				$this->weight = $data['weight'];
			
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
	function create_position($screen_id_in, $feed_id_in, $field_id_in, $weight_in = DEFAULT_WEIGHT){
		if($this->set){
			return true; //You've already got an object!
		} else {
		  if(!is_numeric($screen_id_in) || !is_numeric($feed_id_in) || !is_numeric($field_id_in) || !is_numeric($weight_in)){
		    return false;
		  }
			$sql = "SELECT COUNT(id) FROM position WHERE screen_id = $screen_id_in AND feed_id = $feed_id_in AND field_id = $field_id_in";
			$res = sql_query($sql);
			$data = (sql_row_keyed($res,0));
			if( $data['COUNT(id)'] != 0){
				return false;  //Implying the mapping already exists
			} else {
				$sql = "INSERT INTO position (screen_id, feed_id, field_id, weight) VALUES ($screen_id_in, $feed_id_in, $field_id_in, $weight_in)";
				$res = sql_query($sql);
				if($res){
					$this->id = sql_insert_id();
					$this->screen_id = $screen_id_in;
					$this->feed_id = $feed_id_in;
					$this->field_id = $field_id_in;
					$this->weight = $weight_in;
				
					$this->set = true;
					return true;
				} else {
					return false;
				}
			}
        }
	
	}
	
	//Updates weights ONLY!
	function set_properties(){
	  if(!is_numeric($this->weight)){
	   return false;
	  }
		$sql = "UPDATE position SET weight = '$this->weight' WHERE id = $this->id LIMIT 1";
		$res = sql_query($sql);
		if($res != 0){
			return true;
		} else {
			return false;
		}
	}
	
	function delete_me(){
		return $this->destroy();
	}
	function destroy(){
			if($this->set){
			$sql = "DELETE FROM position WHERE id = '$this->id' LIMIT 1";
			$res = sql_query($sql);
			if($res != 0){
				$this->id = '';
				$this->screen_id = '';
				$this->feed_id = '';
				$this->field_id = '';
				$this->weight = '';
				
				$this->set = false;
				return true;
			} else {
				return false;
			}
		
		} else {
			return true;
		}
	}
}

?>
