<?
class ScreenDriver{
	var $screen_id;
	
	function __construct($screen_id){
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
	var $type_id;
	
	function __construct($screen_id, $field_id){
	    session_start();

	    $this->screen_id = $screen_id;
	    $this->field_id = $field_id;
		if(!$_SESSION['timeline'][$field_id])
		    $this->construct_timeline();
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
			$content[$data['feed_id']][] = $data['content_id'];
			$weight[$data['feed_id']] = $data['weight'];

			$size = max($size, $weight[$data['feed_id']] * count($content[$data['feed_id']]));
		}
		
		$matrix = array();
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
		
		while(count($matrix[0])) {
		    foreach($matrix as &$row) {
		        $content_id = array_shift($row);
		        if(isset($content_id))
		            $_SESSION['timeline'][$this->field_id][] = $content_id;
		    }
		}
    }
	
	function content_details(){
		if($_SESSION['timeline'][$this->field_id]){
		    if(count($_SESSION['timeline'][$this->field_id])){
		        $content_id = array_shift($_SESSION['timeline'][$this->field_id]);
		        $sql = "SELECT c.content, c.mime_type, c.duration FROM content c
                      LEFT JOIN feed_content fc ON c.id = fc.content_id WHERE c.id = $content_id AND moderation_flag = 1;";
		        $res = sql_query($sql);
		        if($res && sql_count($res)){
			        $data = (sql_row_keyed($res,0));
			        $json['content'] = stripslashes($data['content']);
			        $json['mime_type'] = stripslashes($data['mime_type']);
			        $json['duration'] = $data['duration'];
			
			        if($data['mime_type'] == 'text/time'){ //This executes time code
				        $json['mime_type'] = 'text/html';
				        $json['content'] = date($data['content']);
			        }

			        $sql = "UPDATE screen SET last_updated = NOW() WHERE id = $this->screen_id LIMIT 1";
			        sql_command($sql);
			
			        return $json;
			    } else {
			        $this->construct_timeline();
			        return $this->content_details();
			    }
		    } else {
		        $this->construct_timeline();
		        return $this->content_details();
		    }
		} else {
			return false;
		}
	}
}

?>
