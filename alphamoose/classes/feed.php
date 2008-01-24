<?
/*
Class: Feed
Status: Getting there....
Functionality:  
Comments:
        create_feed             Creates a new feed
        set_properties	Sets any properties back to the db
        content_add		Adds content to the feed, based on the content_id and an optional moderation flag
        coontent_count	Counts all the content in a feed that match an optional mod flag
        content_list		Lists all the content in a feed, again with the mod flag junk
        content_mod		Moderates content in a feed, requires content ID and mod flag
		

*/
class Feed{
        var $id;
        var $name;
        var $group_id;
        
	var $set;

        function __construct($id = ''){
		if($id != ''){
			$sql = "SELECT * FROM feed WHERE id = $id LIMIT 1";
            $res = sql_query($sql);
            if($res != 0){
                $data = (sql_row_keyed($res,0));
                $this->id = $data['id'];
                $this->name = $data['name'];
				$this->group_id = $data['group_id'];

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

	function create_feed($name_in, $group_in){
		if($set == true){
			return false; //We already have a feed established here
		} else {
			$sql = "INSERT INTO feed (name, group_id) VALUES ($name_in, $group_in)";
                        $res = sql_query($sql);
                        if($res){
                                $sql_id = sql_insert_id();

                                $this->id = $sql_id;
                                $this->name = $name_in;
                                $this->group_id = $group_in;
                                $this->set = true;

                                return true;
                        } else {
                                return false;
                        }

                }
        }

	function set_properties(){
		$sql = "UPDATE feed SET name = '$this->name', group_id = '$this->group_id' WHERE id = $this->id LIMIT 1";
		$res = sql_query($sql);
                if($res){
                        return true;
                } else {
                        return false;
                }

        }

	function content_add($content_in, $mod_in = ''){
		if($mod_in != 0 || $mod_in != 1 || $mod_in != ''){ //Don't let a stupid value in
			$mod_in = '';
		}
		$sql = "INSERT INTO feed_content (feed_id, content_id, moderation_flag) VALUES ($this->id, $content_in, $mod_in)";
		$res = sql_query($sql);
		if($res){
                        return true;
                } else {
                        return false;
                }
        }
	}
	
	function content_count($mod_flag="*"){
		$sql = "SELECT COUNT(content_id) FROM feed_content WHERE moderation_flag = '$mod_flag'";
		$res = sql_query($sql);
		$data = (sql_row_keyed($res,0));
		return $data['COUNT(content_id)'];
	}
	function content_list($mod_flag="*"){
		$sql = "SELECT * FROM feed_content WHERE moderation_flag = '$mod_flag'";
		$res = sql_query($sql);
		$i=0;
		while($row = sql_row_keyed($res,$i)){
		    $data[$i]['content_id'] = $row['content_id'];
			$data[$i]['moderation_flag'] = $row['moderation_flag'];
		    $i++;
		}
		return $data;
	}
	function content_mod($cid, $mod_flag=' '){
		$sql = "UPDATE feed_content SET moderation_flag = $mod_flag WHERE feed_id = $this->id AND content_id = '$mod_flag' LIMIT 1";
		$res = sql_query($sql);
		if($res){
			return true;
		} else {
			return false;
		}		
	}
}

?>
