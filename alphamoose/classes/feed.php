<?
/*
Class: Feed
Status: New
Functionality:  
Comments:
        create_user             Creates a new user

*/
class User{
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

                                $this->id = $sq                        $res = sql_query($sql);
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
                if($res != 0){
                        return true;
                } else {
                        return false;
                }

        }

	function add_content($content_in, $mod_in = ''){
		if($mod_in != 0 || $mod_in != 1){
			$mod_in = 0;
		}
		$sql = "INSERT INTO feed_content (feed_id, content_id, moderation_flag) VALUES ($this->id, $content_in, $mod_in)";
		if($res != 0){
                        return true;
                } else {
                        return false;
                }
        }


	}         

}

?>
