<?
/*
Class: Feed
Status: Done maybe
Functionality:  
        create_feed             Creates a new feed
        set_properties	Sets any properties back to the db
        content_add		Adds content to the feed, based on the content_id and an optional moderation flag
        content_remove	Removes a piece of content from a feed
        coontent_count	Counts all the content in a feed that match an optional mod flag
        content_list		Lists all the content in a feed, again with the mod flag junk
        content_mod		Moderates content in a feed, requires content ID and mod flag
        list_all		Lists all the feeds in the system, optional WHERE syntax
        list_by_type   Lists all feeds based on the type of their content
        get_all		Gets all the feeds in the system, optional WHERE syntax
        priv_get		Gets all the feeds that an object (usr/scr) can access on a per action basis
        priv_test		Test if a user can see a specific feed
        destroy		Deletes a feed, all content mapped to the feed, and scales all the fields up appropriately
Comments:		
	Working on adding the dynamic feeds, like RSS.
	Added the ability to list feeds based on a type, for content listing
	Cleaned
*/
class Feed{
    var $id;
    var $name;
    var $group_id;
	
	var $type; //Stores the type of the feed, basic or advanced
	var $dyn_id; //Stores the id of the feed if has a reference in the dynamic feed table (for type == advanced)
	var $dyn;	//Holds a Dynamic object if the feed is dynamic
    
	var $status;
	var $set;

    function __construct($id = '', $dyn_allowed = true){
		if($id != ''){
			$sql = "SELECT * FROM feed WHERE id = $id LIMIT 1";
            $res = sql_query($sql);
            if($res){
                $data = (sql_row_keyed($res,0));
                $this->id = $data['id'];
                $this->name = $data['name'];
				$this->group_id = $data['group_id'];
				$this->type = $data['type'];
				if($this->type != 0){
					$this->dyn_id = $data['dynamic_id'];
					if($dyn_allowed){ //We do this to prevent a loop of feeds ->dynamic ->feeds->...
						$this->dyn = new Dynamic($this->dyn_id, $this->id);
					}
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

	function create_feed($name_in, $group_in, $type_in = 0){
		if($this->set == true){
			return false; //We already have a feed established here
		} else {
			//Begin testing/cleaning block
			$name_in = escape($name_in);
			if(!is_numeric($group_in) || !is_numeric($type_in)){
				$this->status = "Unknown Error"; //Aka they are playing with the post data!
				return false;
			}
			//End testing/cleaning block
			$sql = "INSERT INTO feed (name, group_id, type) VALUES ('$name_in', $group_in, $type_in)";
            		$res = sql_query($sql);
                	if($res){
                    		$sql_id = sql_insert_id();

                    		$this->id = $sql_id;
                    		$this->name = stripslashes($name_in);
                    		$this->group_id = $group_in;
				$this->type = $type_in;
							
                    		$this->set = true;

				$notify = new Notification();
	                        $notify->notify('feed', $this->id, 'group', $this->group_id, 'new');

                    		return true;
                	} else {
                    		return false;
                	}
        	}
    	}
	//Sets the properties back to the database
	function set_properties(){
		$name_clean = escape($this->name);
		if(!is_numeric($this->group_id)){
				$this->status = "Unknown Error"; //Aka they are playing with the post data!
				return false;
		}
		if(!is_numeric($this->type)){
				$this->status = "Unknown Error"; //Aka they are playing with the post data!
				return false;
		}
		$sql = "UPDATE feed SET name = '$name_clean', group_id = '$this->group_id', type = '$this->type' WHERE id = $this->id LIMIT 1";
		$res = sql_query($sql);
        if($res){
	    $notify = new Notification();
            $notify->notify('feed', $this->id, 'user', $_SESSION['user']->id, 'update');
            return true;
        } else {
            return false;
        }
    }
	//Add a content to a feed
	function content_add($content_in, $mod_in = 'NULL'){
		if(!is_numeric($content_in)){
				$this->status = "Please send the content id"; //Aka they are playing with the post data!
				return false;
		}
		if($mod_in != 0 && $mod_in != 1 && $mod_in != 'NULL'){ //Don't let a stupid value in
			$mod_in = 'NULL';
		}
		$sql = "INSERT INTO feed_content (feed_id, content_id, moderation_flag) VALUES ($this->id, $content_in, $mod_in)";
		$res = sql_query($sql);
		if($res){
			if($this->type == 0){  //Dont log dynamic feeds
				$notify = new Notification();
                        	$notify->notify('feed', $this->id, 'content', $content_in, 'add');
           		}
			return true;
        	} else {
            		return false;
        	}
    }
	
	//Remove content from a feed
	function content_remove($content_in){
		$sql = "DELETE FROM feed_content WHERE feed_id = $this->id AND content_id = $content_in LIMIT 1";
		sql_query($sql);
		if($this->type == 0){  //Dont log dynamic feeds
                        $notify = new Notification();
                	$notify->notify('feed', $this->id, 'content', $content_in, 'remove');
                }
		return true;
	}

	//Count # of content in a feed based on moderation status
	function content_count($mod_flag=''){
		if($mod_flag != ''){
			$mod_where = "AND moderation flag LIKE '$mod_flag'";
		} else {
			$mod_where = "";
		}
		$sql = "SELECT COUNT(content_id) FROM feed_content WHERE feed_id = $this->id $mod_where";
		if($res = sql_query($sql)){
			$data = (sql_row_keyed($res,0));
			return $data['COUNT(content_id)'];
		} else {
			return 0;
		}
	}
	//List all content in a feed based on moderation status
	function content_list($mod_flag=''){
		if($mod_flag == 'NULL'){
			$mod_where = "AND moderation_flag IS NULL";
		} elseif($mod_flag != ''){
			$mod_where = "AND moderation_flag LIKE '$mod_flag'";
		} else {
			$mod_where = "";
		}
		$sql = "SELECT * FROM feed_content WHERE feed_id = $this->id $mod_where";
		$res = sql_query($sql);
		$i=0;
		while($row = sql_row_keyed($res,$i)){
		    $data[$i]['content_id'] = $row['content_id'];
		    $data[$i]['moderation_flag'] = $row['moderation_flag'];
		    $i++;
		}
		if(isset($data)){
			return $data;
		} else {
			return false;
		}
	}
	//Moderate content: Approve or deny
	function content_mod($cid, $mod_in='NULL'){
		if($mod_in != 0 && $mod_in != 1 && $mod_in != 'NULL'){ //Don't let a stupid value in
			$mod_in = 'NULL';
		}
		$sql = "UPDATE feed_content SET moderation_flag = $mod_in WHERE feed_id = $this->id AND content_id = '$cid' LIMIT 1";
		$res = sql_query($sql);
		if($res){
			$notify = new Notification();
			if($mod_in == 1){
                        	$notify->notify('feed', $this->id, 'content', $cid, 'approve');
			} elseif($mod_in == 0){
				$notify->notify('feed', $this->id, 'content', $cid, 'deny');
			}
			return true;
		} else {
			return false;
		}		
	}
	//List all feeds, optional WHERE syntax
	function list_all($where = ''){
		$sql = "SELECT * FROM feed $where";
		$res = sql_query($sql);
		$i=0;
		$found = false;
		while($row = sql_row_keyed($res,$i)){
			$found = true;
		    $data[$i]['id'] = $row['id'];
			$data[$i]['name'] = $row['name'];
			$data[$i]['group_id'] = $row['group_id'];
		    $i++;
		}
		if($found){
			return $data;
		} else {
			return false;
		}
	}
	//List all feeds, based on type
	function list_all_by_type($where = 'WHERE  type.id IS NOT NULL'){
		$sql = "SELECT feed.id, feed.name, type.id as t_id, type.name as t_name
				FROM feed
				LEFT JOIN feed_content ON feed_content.feed_id = feed.id
				LEFT JOIN content ON feed_content.content_id = content.id
				LEFT JOIN type ON content.type_id = type.id
				$where
				GROUP BY feed.id, type.id";
		$res = sql_query($sql);
		$i=0;
		$found = false;
		while($row = sql_row_keyed($res,$i)){
		    $found = true;
			$data[$i]['id'] = $row['id'];
			$data[$i]['name'] = $row['name'];
			$data[$i]['type_id'] = $row['t_id'];
			$data[$i]['type_name'] = $row['t_name'];
		    $i++;
		}
		if($found){
			return $data;
		} else {
			return false;
		}
	}
	
	//List all feeds, optional WHERE syntax
	function get_all($where = ''){
		$sql = "SELECT * FROM feed $where";
		$res = sql_query($sql);
		$i=0;
		$found = false;
		while($row = sql_row_keyed($res,$i)){
			$found = true;
		    $data[] = new Feed($row['id']);
		    $i++;
		}
		if($found){
			return $data;
		} else {
			return false;
		}
	}
	//Returns an array of feeds that the object has access to do an action with
	function priv_get($obj, $action='list'){
		if($action == 'subscribe'){
			$scr_group = $obj->group_id;
			$sql = "SELECT id FROM feed WHERE type = 0 OR type = 1 OR type = 2 OR (type = 3 AND group_id = $scr_group)";
		}elseif($action == 'content'){
			$group_string = implode(',',$obj->groups);
			$sql = "SELECT id FROM feed WHERE type = 0 OR (type = 2 AND group_id IN ($group_string)) OR (type = 3 AND group_id IN ($group_string))";
		}elseif($action == 'list'){
			$group_string = implode(',',$obj->groups);
			$sql = "SELECT id FROM feed WHERE type = 0 OR type = 1 OR type = 2 OR (type = 3 AND group_id IN ($group_string))";
		}else{
			return false;
		}
		$res = sql_query($sql);
		$i=0;
		$found = false;
		while($row = sql_row_keyed($res,$i)){
		    $found = true;
		    $data[] = new Feed($row['id']);
		    $i++;
		}
		if($found){
			return $data;
		} else {
			return false;
		}
	}
	//Test if a user has access to see a specific feed
	function priv_test($obj, $feed_id){
		$group_string = implode(',',$obj->groups);
		$sql = "SELECT COUNT(id) FROM feed WHERE id = $feed_id AND (type = 0 OR type = 1 OR type = 2 OR (type = 3 AND group_id IN ($group_string)))";
		if($res = sql_query1($sql)){
			return $res;
		} else {
			return false;
		}
	}
	//Destroys a feed.  Roar
	function destroy(){
		$sql = "DELETE FROM feed_content WHERE feed_id = $this->id";
		$res = sql_query($sql);
		if(!$res){
			return false; //Error unmapping content!
		}
		$sql1 = "SELECT field_id, screen_id FROM position WHERE feed_id = $this->id";
		$res1 = sql_query($sql1);
		if(!$res1){
			return false; //Error grabbing positions/fields
		}
		$i=0;
		while($row = sql_row_keyed($res1,$i)){
		    $field = new Field($row['field_id'],$row['screen_id']);
			$field->delete_feed($this->id);
			$field->rebalance_scale();
		    $i++;
		}
		
		$sql = "DELETE FROM feed WHERE id = $this->id";
		$res = sql_query($sql);
		if(!$res){
			return false; //Error with the final delete!!!
		}
		$notify = new Notification();
		$notify->notify('feed', $this->id, 'user', $_SESSION['user']->id, 'delete');
		//Then we just clear the variables
		$this->id = '';
		$this->name = '';
		$this->group_id-'';
		$this->set = false;
		return true;
	}
}

?>
