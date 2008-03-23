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
	var $last_update;
	
	var $feed;
	var $content; //An array of content we create from the RSS feed
	
	var $feed_set;
	var $set;
	
	function __construct($id = '', $feed_id=''){
		if($id != ''){
			$sql = "SELECT * FROM dynamic WHERE id = $id LIMIT 1";
			$res = sql_query($sql);
            if($res){
                $data = (sql_row_keyed($res,0));
                $this->id = $data['id'];
                $this->type = $data['type'];
				$this->path = stripslashes($data['path']);
				$this->rules = unserialize($data['rules']);
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
		$return = true;
		if($this->type == 1){
			$return = $this->rss_update();
		} else {
			return false;
		}
		if($return){
			$ret_val = $this->add_content();
			if($ret_val){
				$this->log_update();
				return true;
			} else {
				return false;
			}
		}
	}
	
	function rss_update(){
		if($xml = simplexml_load_file($this->path)){
			
			$title = $xml->xpath($this->rules['title']['path']);
			$title = $title[$this->rules['title']['item_num']];
			$title = $this->regex_engine($title, $this->rules['title']['regex']);

			foreach ($xml->xpath($this->rules['item']['path']) as $item){
				$i_title = $item->xpath($this->rules['item']['title']['path']);
				$i_title = $i_title[$this->rules['item']['title']['item_num']];
				$i_sub = $item->xpath($this->rules['item']['sub']['path']);	
				$i_sub = $i_sub[$this->rules['item']['sub']['item_num']];

				$i_title = $this->regex_engine($i_title, $this->rules['item']['title']['regex']);
				
				$i_sub = $this->regex_engine($i_sub, $this->rules['item']['sub']['regex']);

				$data[] = "$i_title $i_sub";
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
			return false;
		}
	}
	function add_content(){
		if($this->feed_set){
			$name = $this->feed->name;
		} else {
			$name = "Dynamic Content";
		}
		foreach($this->content as $key =>$item){
			$lower = $key  * $this->rules['items_per_content'] + 1;
			$upper = $lower + $this->rules['items_per_content'] - 1;
			
			$c_name = $name . " ($lower-$upper)";
			$c_owner = 0; //Content is owned by the system
			$mime_type = "text/html";
			$type_id = 1;
			$duration = 10000;
			$start_time = date("Y-m-d") . " 00:00:00";
			$end_time = date("Y-m-d", strtotime("tomorrow")) . " 00:00:00";
	
			$return = true; //This will hold any errors we hit adding content
			$obj = new Content();
			if($obj->create_content($c_name, $c_owner, $item, $mime_type, $type_id, $duration, $start_time, $end_time)){
				$c_id = $obj->id;
				$new_ids[] = $c_id;
				$return = $return * true;
			} else {
				$return = $return * false;
			}
		}
		if($return){ //Test for errors adding content
			$old_ids = $this->feed->content_list(); //Get all the content currently in the feed
			if($old_ids){
				foreach($old_ids as $id){ //Reformat it so its only the id
					$ids[] = $id['content_id'];
				}
			}
			$return = true;
			foreach($new_ids as $id){
				$return = $return * ($this->feed->content_add($id, 1)); //Since we auto approve it
			}
			if(!$return){
				return false; //We had problems adding content to the right feed
			}
			if($old_ids){
				return $this->remove_content($ids); //Finally we remove the old ones.  We do this last so there is always content, we can live with duplicates for a few seconds
			} else {
				return true;
			}
		} else {
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
			}
		}
		return $var_in;
	
	}
	
}
?>
