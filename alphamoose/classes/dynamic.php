<?
/*
Class: Dynamic
Status: Just getting going
Functionality:  
        create_dynamic            Creates a new dynamic thing //NOT\\
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
	var $items_per_content;
	
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
				$this->path = $data['path'];
				$this->rules = $data['rules'];
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
			$this->items_per_content = 3;
			$return = $this->rss_update();
		} else {
			return false;
		}
		if($return){
			return $this->add_content();
		}
	}
	
	function rss_update(){
		if($xml = simplexml_load_file($this->path)){
		
			$title = $xml->channel->title;
	
			$title = eregi_replace('\s*rensselaer\s*', '', $title);
			$title = eregi_replace('\s*-+.*','',$title);
		
			$title = "<h1>$title</h1>";

			foreach ($xml->channel->item as $item){
				$i_title = $item->title;
				$i_sub = $item->description;	

				$i_title = eregi_replace('\s*rensselaer\s*', '', $i_title);
				$i_title = eregi_replace('( - )+.*','',$i_title);

				$i_sub = eregi_replace('\s*rensselaer\s*', '', $i_sub);
				$i_sub = eregi_replace('\s*\.+.*','',$i_sub);
				$i_sub = eregi_replace('\(.*\)','',$i_sub);

				$data[] = "<h2>$i_title</h2><h3>$i_sub</h3>";
			}
			$content_count = floor((count($data) + ($this->items_per_content - 1)) / $this->items_per_content);
			foreach ($data as $key => $content_text){
				$cur_count = floor($key / $this->items_per_content);
			
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
			$upper = $key + $this->items_per_content;
			$c_name = $name . " ($key-$upper)";
			$c_owner = 0; //Content is owned by the system
			$mime_type = "text/html";
			$type_id = 1;
			$duration = 5000;
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
			if($old_ids && $return){
				return $this->remove_content($ids); //Finally we remove the old ones.  We do this last so there is always content, we can live with duplicates for a few seconds
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
	
}
?>