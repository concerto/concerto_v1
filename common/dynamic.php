<?
/*
Class: Dynamic
Status: Just getting going
Functionality:  
        update			updates a dynamic feed.  Gets new content, removes old content
Comments:		

*/
class Dynamic{
	var $id;
	var $type;
	var $path;
	var $rules;
	var $update_interval;
	var $last_update;
	var $status;
	
	var $feed;
	var $content; //An array of content we create from the RSS feed
	
	var $feed_set;
	var $set;
	
	function __construct($id = '', $feed_id=''){
		$this->status = "";
		if($id != ''){
			$sql = "SELECT * FROM dynamic WHERE id = $id LIMIT 1";
			$res = sql_query($sql);
            if($res){
                $data = (sql_row_keyed($res,0));
                $this->id = $data['id'];
                $this->type = $data['type'];
		$this->path = stripslashes($data['path']);
		$this->rules = unserialize($data['rules']);
		$this->update_interval = $data['update_interval'];
		$this->last_update = $data['last_update'];
				
		if($feed_id != ''){
			$this->feed = new Feed($feed_id, false);  //The false is critical here!
			$this->feed_set = true;
		} else {
			$this->feed_set = false;
		}
			
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
	
	function update(){
		//Determine if we want an update before we run one
		if((time() - strtotime($this->last_update)) >= $this->update_interval){
			$return = true;
			if($this->type == 1){
				$return = $this->rss_update();
			} else {
				$this->status .= "Unknown update handler. ";
				return false;
			}
			if($return){
				$ret_val = $this->add_content();
				if($ret_val){
					$this->log_update();
					return true;
				} else {
					$this->status .= "Failure to add content. ";
					return false;
				}
			} else {
				$this->status .= "Updated Failed";
				return false;
			}
		} else {
			return true; //No update was run because we just ran one
		}
	}
	
	function rss_update(){
		if($xml = simplexml_load_file($this->path)){
			
			$title = $xml->xpath($this->rules['title']['path']);
			$title = $title[$this->rules['title']['item_num']];
			$title = $this->regex_engine($title, $this->rules['title']['regex']);
			$data = array(); //Set it up incase we don't have any the loops might break
			
			foreach ($xml->xpath($this->rules['item']['path']) as $item){
				$i_title = $item->xpath($this->rules['item']['title']['path']);
				$i_title = $i_title[$this->rules['item']['title']['item_num']];
				$i_sub = $item->xpath($this->rules['item']['sub']['path']);	
				$i_sub = $i_sub[$this->rules['item']['sub']['item_num']];

				$i_title = $this->regex_engine($i_title, $this->rules['item']['title']['regex']);
				
				$i_sub = $this->regex_engine($i_sub, $this->rules['item']['sub']['regex']);

				$temp_content = "$i_title $i_sub";

				$data[] = $temp_content;
			}
			$content_count = floor((count($data) + ($this->rules['items_per_content'] - 1)) / $this->rules['items_per_content']);
			foreach ($data as $key => $content_text){
				$cur_count = floor($key / $this->rules['items_per_content']);
			
				if(isset($this->content[$cur_count])){
					$this->content[$cur_count] = $this->content[$cur_count] . $content_text;
				} else {
					$this->content[$cur_count] = $title . $content_text;
				}
			}
			return true;
		} else {
			$this->status .= "Couldnt open RSS. ";
			return false;
		}
	}
	function add_content(){
		if($this->feed_set){
			$name = $this->feed->name;
		} else {
			$name = "Dynamic Content";
		}
		//Begin Generic properties for all content.  Generic is capitalized for a reason.
		$c_owner = 0; //Content is owned by the system
		$mime_type = "text/html";
		$type_id = 1;
		$duration = 10000;
		$start_time = date("Y-m-d") . " 00:00:00";
		$end_time = date("Y-m-d", strtotime("tomorrow")) . " 00:00:00";
		//End Generic properties for all content
		
		//We can overwrite some of those properties
		if(isset($this->rules['duration'])){
			$duration = $this->rules['duration'];
		}
		if(isset($this->rules['type_id'])){
			$type_id = $this->rules['type_id'];
		}
		if(isset($this->rules['mime_type'])){
			$mime_time = $this->rules['mime_type'];
		}
		//End overwriting them

		$max_digits = floor(1+log($this->rules['items_per_content']*count($this->content),10));
		$existing_count = $this->feed->content_count();

		//Turn off notifications if they are not already being controlled
		if(!defined("NOTIF_OFF")){
			define("NOTIF_OFF",1);
		}
		while($existing_count < count($this->content)){
			$obj = new Content();
			if($obj->create_content("New Content", $c_owner, "", $mime_type, $type_id, $start_time, $end_time)){
				//We can't forget to add it to that feed!
				$this->feed->content_add($obj->id, 0, 0, $duration);
				$existing_count++;
			} else {
				$this->status .= "Error creating needed content. ";
				return false; //Bomb bomb bomb.  There is a story behind that, yes
			}
		}
		$content_objs = $this->feed->content_get();
		if(isset($this->content) && count($this->content) > 0){
			foreach($this->content as $key =>$item){
				$lower = $this->zero_pad($key  * $this->rules['items_per_content'] + 1, $max_digits);
				$upper = $this->zero_pad($lower + $this->rules['items_per_content'] - 1, $max_digits);
			
				if($upper != $lower){
					$c_name = $name . " ($lower-$upper)";
				} else {
					$c_name = $name . " ($lower)";
				}

				//If there is a pre or post rule, apply them here
                                if(isset($this->rules['pre'])){
                                        $item = $this->rules['pre'] . $item;
                                }
                                if(isset($this->rules['post'])){
                                        $item = $item . $this->rules['post'];
                                }
		
				$return = true; //This will hold any errors we hit adding content
				
				$obj = $content_objs[$key]['content'];
				$obj->name = $c_name;
				$obj->content = $item;
				$obj->start_time = $start_time;
				$obj->end_time = $end_time;
				if($obj->set_properties()){
					$c_id = $obj->id;
					if($content_objs[$key]['moderation_flag'] != 1){ //We need to moderate it!
						if($this->feed->content_mod($c_id, 1, 0)){
							$return = $return * true;
						} else {
							$return = $return * false;
						}
					} else { //No moderation is needed
						$return = $return * true;
					}
				} else {
					$return = $return * false;
				}
			}
		}else{
			$return = true;
		}
		if($return){ //Test for errors before cleaning out the old content
			for($i = count($content_objs) - 1 ; $i >= count($this->content); $i--){
				$obj = $content_objs[$i]['content'];
				//print_r($obj);
				$c_id = $obj->id;
				$this->feed->content_mod($c_id, 0, 0);  //Deny that content
				//We'll clean it out just for fun
				$obj->content = "";
				$obj->name = "Unused dynamic content";
				if($obj->set){
					$obj->set_properties();
				}
			}
			return true;
		} else {
			$this->status .= "Unknown error adding content. ";
			return false;  //Errors adding content!
		}
		
	}
	
	function remove_content($ids){
		$return = true;
		foreach($ids as $id){
			$obj = new Content($id);
			$return = $return * $obj->destroy();
		}
		return $return;
	}
	
	function log_update(){
		$sql = "UPDATE dynamic SET last_update = NOW() WHERE id = $this->id LIMIT 1";
		sql_query($sql);
	}
	function regex_engine($var_in, $ruleset){
		foreach($ruleset as $regex){
			if($regex['search'] == '%t'){
				$regex['search'] = $var_in;
			}
			if($regex['replace'] == '%t'){
				$regex['replace'] = $var_in;
			}
			if($regex['on'] == '%t'){
				$regex['on'] = $var_in;
			}
			//Actually process the regex
			if($regex['type'] == 'eregi_replace'){
				$var_in = eregi_replace($regex['search'], $regex['replace'], $regex['on']);
			}elseif($regex['type'] == 'ereg_replace'){
				$var_in = ereg_replace($regex['search'], $regex['replace'], $regex['on']);
			}
		}
		return $var_in;
	
	}
	function zero_pad($content, $desired_digits){
		if(strlen($content) < $desired_digits){
			$offset = $desired_digits - strlen($content);
			$content = str_repeat('0',$offset) . $content;
			return $content;
		}
		return $content;
	}
}
?>
