<?
/*
Class: Dynamic
Status: Just getting going
Functionality:  
        create_dynamic            Creates a new dynamic thing //NOT\\
Comments:		
DONT USE THIS FOR ANYTHING YET.  ITS ONLY HERE BECAUSE ITS REFERENCED IN FEED
*/
class Dynamic{
	var $id;
	var $type;
	var $path;
	var $rules;
	var $last_update;
	var $feed;
	
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
					$feed = new Feed($feed_id, false);  //The false is critical here!
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
		if($this->type == 1){
			return $this->rss_update();
		} else {
			return false;
		}
	}
	
	function rss_update(){
		$xml = simplexml_load_file($this->path);
		$title = $xml->channel->title;
		
		//Hardcoded Rules to clean title, designed for UEC.  We will port these to "rules" later
		$title = eregi_replace('\s*rensselaer\s*', '', $title);
		$title = eregi_replace('\s*-+.*','',$title);
		
		
		
	}
	
	
	
}
?>