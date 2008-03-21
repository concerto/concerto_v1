<?
/*
Class: Field
Status: New
Functionality:
	set_properties		Writes and changes back to the database, includes all sub-positions
	list_positions		Lists all the positions in a field
	rebalance			Re-Weights all positions to have an equal weight (1/sum)
	rebalance_byweight	Re-Weights all positions based what is set in their weight properties.
	rebalance_scale		Re-Weights all positions by scaling them up... useful if you delete a position
	add_feed			Adds a feed mapping to the field.. aka creates a position
	delete_feed		Deletes a feed mapping.. aka removes a position
	avail_feeds			List all the feeds that haven't been joined to a field
Comments: 
	The rebalance functions will return false if they don't like you, or if the weights don't add up to 1, or very close to one.
	"Very Close" to one is hardcoded in based on the precision defined in the table for the range_l and range_h.

*/

class Field{
	var $id;
	var $name;
	var $template_id;
	var $type_id;
	var $style;
	var $left;
	var $top;
	var $width;
	var $height;
	
	var $screen_id;
	var $screen_set;
	var $screen_pos;
	
	var $set;
	
	function __construct($id_in='', $screen_id_in=''){
		if($id_in != ''){
			$sql = "SELECT * FROM field WHERE id = '$id_in' LIMIT 1";
			$res = sql_query($sql);
			if($res != 0){
				$data = (sql_row_keyed($res,0));
				$this->id = $data['id'];
				$this->name = $data['name'];
				$this->template_id = $data['template_id'];
				$this->type_id = $data['type_id'];
				$this->style = $data['style'];
				$this->left = $data['left'];
				$this->top = $data['top'];
				$this->width = $data['width'];
				$this->height = $data['height'];
				
				$this->set = true;
				
				if($screen_id_in != ''){
					$this->screen_id = $screen_id_in;
					$sql = "SELECT id FROM position WHERE screen_id = $this->screen_id AND field_id = $this->id";
					$res2 = sql_query($sql);
					$i = 0;
					while($pos_row = sql_row_keyed($res2, $i)){
						$pos_id = $pos_row['id'];
						$this->screen_pos[$i] = new Position($pos_id);
						$i++;				
					}
					$this->screen_set = true;
				} else {
					$this->screen_set = false;
				}
			} else {
				return false;
			}
		} else {
			$this->set = false;
			return true;
		}
	
	}
	
	function set_properties(){
		$sql = "UPDATE `field` SET `name` = '$this->name', `template_id` = '$this->template_id', `type_id` = '$this->type_id', `style` = '$this->style', `left` = '$this->left', `top` = '$this->top', `width` = '$this->width', `height` = '$this->height' WHERE `id` = $this->id LIMIT 1";
		$res = sql_query($sql);
		if($res != 0){
			$poss = 1;
			foreach ($this->screen_pos as $pos){
				$poss = $poss * ($pos->set_properties());
			}
			return $poss;
		} else {
			return false;
		}
	
	}
	
	//Lists all the positions in a field
	function list_positions(){
		if($this->screen_set){
			$data = $this->screen_pos;
			return $data;
		} else {
			return false;
		}
	}
	
	//Rebalances all positions to be equally liked
	function rebalance(){
		if($this->screen_set){
			$count = count($this->screen_pos);
			$step = 1 / $count;
			$low = 0;
			$high = $step;
			foreach($this->screen_pos as $pos){
				$pos->range_l = $low;
				$pos->range_h = $high;
				$pos->set_properties();
			
				$low = $high;
				$high = $high+$step;
			}
			if($high > 0.99999){
				return true;
			} else {
				return false;  //This implies we didn't hit a max of 0.99999, where the database will round up to 1.  AKA there might not be a complete distribution.
			}
		} else {
			return false; //A screen object hasn't been bound and gagged yet, hence we can't do anything
		}
	}
	//Rebalances based on the weights stored in each postions.
	function rebalance_byweight(){
		if($this->screen_set){
			$sum = 0;
			foreach($this->screen_pos as $pos){
				$sum += $pos->weight;
			}
			if($sum < 0.99999 || $sum > 1){  //Tests to verify that all the weights add up to 1, or something close enough for the database
				return false;
			} else {
				$low = 0;
				foreach($this->screen_pos as $pos){
					$high = $low + $pos->weight;
					$pos->range_l = $low;
					$pos->range_h = $high;
					$pos->set_properties();
				
					$low = $high;			
				}
				return true;
			}
		} else {
			return false; //See rebalance for comment
		}
	}
	//Rebalances the weights so they sum to 1, helpful if you delete stuff
	function rebalance_scale(){
		if($this->screen_set){
			$sum = 0;
			foreach($this->screen_pos as $pos){
				$sum += $pos->weight;
			}
			$scale = 1/$sum; //The amount each weight needs to grow by
			foreach($this->screen_pos as $pos){
				$pos->weight = $pos->weight * $scale;
			}
			return $this->rebalance_byweight();
		} else {
			return false;
		}
	}
	
	//Adds a feed to a field.. aka a position mapping.  Does not do any weighting, that must be done seperately
	function add_feed($feed_id_in){
		if($this->screen_set){
			foreach($this->screen_pos as $pos){
				if($pos->feed_id == $feed_id_in){
					return true; //The mapping already exists.  Someone cannot see that, maybe they are using an iphone and the screen is tiny. dumb iphone
				}
			}
			$new_pos = new Position();
			if($new_pos->create_position($this->screen_id, $feed_id_in, $this->id)){
				$this->screen_pos[] = $new_pos;
				return true;
			} else {
				return false;
			}
		} else {
			return false; //No screen = no fun :-(
		}
	}
	//Removes a feed mapping, aka a position
	function delete_feed($feed_id_in){
		if($this->screen_set){
			foreach($this->screen_pos as $key => $pos){
				if($pos->feed_id == $feed_id_in){
					if($pos->delete_me()){
						unset($this->screen_pos[$key]);
						return true;
					} else {
						return false;
					}
				}
			}
			return true; //The mapping didn't exist!
		} else {
			return false; //No screen = no fun :-(
		}
	}
	//Returns an array of feed objects that haven't been joined to the field
	function avail_feeds(){
		if($this->screen_set){
			$sql = "SELECT id FROM feed WHERE id NOT IN (SELECT feed_id FROM position WHERE field_id = '$this->id' AND screen_id = '$this->screen_id') ORDER BY id ASC";
			$res = sql_query($sql);
			$i = 0;
			while($feed_row = sql_row_keyed($res, $i)){
				$data[$i] = new Feed($feed_row['id']);
				$i++;
			}
			return $data;
		} else {
			return false;  //No screen = no fun!  Get it through your head!
		}
	}

}

?>