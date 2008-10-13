<?php
class ScreenDriver{
    var $screen_id;
	
    function __construct($screen_id){
        if(!is_numeric($screen_id)){
          return false;
        }
        $sql = "SELECT COUNT(id) FROM screen WHERE id = $screen_id;";
        if(sql_query1($sql)){
            $this->screen_id = $screen_id;
        }
    }
	
    function screen_details(){
        if(isset($this->screen_id)){
            $sql = "SELECT screen.width as screen_width, screen.height as screen_height, template.id as template_id, field.id as field_id, field.left, field.top, field.width, field.height, field.style
			FROM screen
			LEFT JOIN template ON screen.template_id = template.id
			LEFT JOIN field ON field.template_id = template.id
			WHERE screen.id = '$this->screen_id'
			GROUP BY field.id;";
            $res = sql_query($sql);
            $i = 0;
            while($data = sql_row_keyed($res,$i++)){
                $width = $data['screen_width'];
                $height = $data['screen_height'];
                $screen_details['screen']['template_id'] = $data['template_id'];
                $screen_details['fields'][$data['field_id']]['left'] = $data['left'] * $width;
                $screen_details['fields'][$data['field_id']]['top'] = $data['top'] * $height;
                $screen_details['fields'][$data['field_id']]['width'] = $data['width'] * $width;
                $screen_details['fields'][$data['field_id']]['height'] = $data['height'] * $height;
                $screen_details['fields'][$data['field_id']]['style'] = $data['style'];
            }
            return $screen_details;
        } else {
            return false;
        }
    }
}

class ContentDriver{
    var $screen_id;
    var $field_id;
    var $content_id;
    var $feed_id;
    var $type_id;
	
    function __construct($screen_id, $field_id){
        session_start();
 
        if(is_numeric($screen_id) && is_numeric($field_id)){        
             $this->screen_id = $screen_id;
             $this->field_id = $field_id;
	     $sql = "SELECT type_id FROM field WHERE id = $field_id";
             $res = sql_query($sql);
	     $data = sql_row_keyed($res,0);
	     $this->type_id = $data['type_id'];

             if(!$_SESSION['timeline'][$field_id]){
                $this->construct_timeline();
             }
        }
    }
	
    private function construct_timeline(){
        unset($_SESSION['timeline'][$this->field_id]);
        
        $sql = "SELECT feed.id as feed_id, position.weight as weight, content.id as content_id
                FROM position
                LEFT JOIN field ON position.field_id = field.id
                LEFT JOIN feed ON position.feed_id = feed.id
                LEFT JOIN feed_content ON feed.id = feed_content.feed_id
                LEFT JOIN content ON feed_content.content_id = content.id
                WHERE position.screen_id = $this->screen_id
                AND field.id = $this->field_id
                AND field.type_id = content.type_id
                AND feed_content.moderation_flag = 1
                AND (content.start_time < NOW() OR content.start_time IS NULL)
                AND (content.end_time > NOW() OR content.end_time IS NULL)
                GROUP BY content.id;";
        $res = sql_query($sql);
        $size = 0;
        $i = 0;
        while($data = sql_row_keyed($res,$i++)){
            $item['c_id'] = $data['content_id'];
            $item['f_id'] = $data['feed_id'];
            $content[$data['feed_id']][] = $item;
            $weight[$data['feed_id']] = $data['weight'];

            $size = max($size, $weight[$data['feed_id']] * count($content[$data['feed_id']]));
        }
		
        $matrix = array();
	if(sizeof($content) > 0){
	  foreach($content as $feed_id => $feed){
            	$row = array();
            foreach(range(1, $weight[$feed_id]) as $i){
                shuffle($feed);
                $row = array_merge($row, $feed);
            }
		    
            while(count($row) < $size) {
                array_splice($row, rand(0, count($row)), 0, array(NULL));
            }
		    
            $matrix[] = $row;
          }
	}else{
		exit(0); //There is no content. We cannot do anything
	}
        while(count($matrix[0])) {
            foreach($matrix as &$row) {
                $content_itm = array_shift($row);
                if(isset($content_itm))
                    $_SESSION['timeline'][$this->field_id][] = $content_itm;
            }
        }
    }

    function content_details(){
        if($this->content_id){
                $content_id = $this->content_id;
                $sql = "SELECT c.id, c.content, c.mime_type, fc.duration FROM content c
                        LEFT JOIN feed_content fc ON c.id = fc.content_id WHERE c.id = $content_id AND moderation_flag = 1;";
                $res = sql_query($sql);
                if($res && sql_count($res)){
                    $data = (sql_row_keyed($res,0));
                    $this->content_id = $data['id'];
                    $json['content'] = stripslashes($data['content']);
                    $json['mime_type'] = stripslashes($data['mime_type']);
                    $json['duration'] = $data['duration'];
			
                    if($data['mime_type'] == 'text/time'){ //This executes time code
                        $json['mime_type'] = 'text/html';
                        $json['content'] = date($data['content']);
                    }
                    $this->log_back();
                    return $json;
                } else {
                    $this->construct_timeline();
                    $this->get_content();
                    return $this->content_details();
                }
         } else {
             $this->construct_timeline();
             $this->get_content();
             return $this->content_details();
         }
    }
    
    function get_content(){
        if($_SESSION['timeline'][$this->field_id]){
            if(count($_SESSION['timeline'][$this->field_id])){
                $content_itm = array_shift($_SESSION['timeline'][$this->field_id]);
            } else {
                $this->construct_timeline();
                $content_itm = array_shift($_SESSION['timeline'][$this->field_id]);
            }
            $this->content_id = $content_itm['c_id'];
            $this->feed_id = $content_itm['f_id'];
            return true;
        } else {
            return false;
        }
    }

    function log_back(){
        $ip = $_SERVER['REMOTE_ADDR'];
	$screen = new Screen($this->screen_id);
	$screen->status_update($ip); //Update the screen last updated and ip stuff
	if($screen->get_powerstate()){
	        $sql = "UPDATE position SET display_count = display_count + 1 ";
		$sql .= "WHERE screen_id = $this->screen_id AND field_id = $this->field_id AND feed_id = $this->feed_id LIMIT 1"; 
        	sql_command($sql);

        	$sql = "UPDATE feed_content SET display_count = display_count + 1 WHERE feed_id = $this->feed_id AND content_id = $this->content_id LIMIT 1";
        	sql_command($sql);
	}
        return true;
    }

    //EMS Display code.
    //The feed defined in EMS_FEED_ID has priority over all other feeds.  If it has content it will be displayed.
    function ems_check(){
        if(defined('EMS_FEED_ID') && EMS_FEED_ID != 0){
            $ems_feed = new Feed(EMS_FEED_ID);
            if($ems_feed->content_count(1) > 0){
		//It appears there is an emergency of sorts
                $contents = $ems_feed->content_list_by_type($this->type_id,1);
		if(!$contents){
                    //There is no EMS content for this location
                    return false;
                } else {
                    $ems_c_id = array_rand($contents,1);
                    $this->content_id = $ems_c_id;
                    return true;
                }
            } else {
                //The feed is empty.  All is quiet on the western front
                return false;
            }
        } else {
            //EMS hasn't been setup
            return false;
        }
    }
    //End EMS Display Code.

}
?>
