<?
/*
Class: Driver
Status: Working, with the time stuff
Functionality:
	screen_details	returns an array of details describing the screen, fields, template, etc
	get_feed		finds and stores the next feed needed in the $feed_id variable
	get_content		finds and stores the next content in $content_id
	content_details
Comments:
Usage might look something like this...
$obj = new Driver(screen_id);
echo json_encode($obj->screen_details());

$obj2 = new Driver($screen_id, $field_id);
$obj2->get_feed();
$obj2->get_content();
echo json_encode($obj2->content_details());
Cleaned
*/

class Driver{
	var $screen_id;
	var $field_id;
	var $type_id;
	var $feed_id;
	var $last_content_id;
	
	var $set;
	var $error;
	
	function __construct($data1_in='', $data2_in=''){
		if(($data1_in != '') && ($data2_in != '')){  //For a screen/field call
			$this->screen_id = $data1_in;
			$this->field_id = $data2_in;
			
			$sql = "SELECT `type_id` FROM `field` WHERE id = $this->field_id";
			$res = sql_query($sql);
			$data = (sql_row_keyed($res,0));
			$this->type_id = $data['type_id'];
			
			$this->error = 0;
			$this->set = true;
		} else if(($data1_in != '') && ($data2_in == '')){ //For a mac call
			$this->screen_id = $data1_in;
			$this->set = true;
		} else {
			$this->set = false;
		}
	}
	
	function screen_details(){
		if(isset($this->screen_id)){
			$sql = "SELECT screen.width, screen.height, template.id as template_id, template.filename FROM screen
			LEFT JOIN template ON screen.template_id = template.id
			WHERE screen.id = '$this->screen_id' LIMIT 1";
			$res = sql_query($sql);
			if($res != 0 && ($data = sql_row_keyed($res,0))){
				//$screen_details['screen']['width'] = $data['width'];
				//$screen_details['screen']['height'] = $data['height'];
				//$screen_details['screen']['template'] = $data['filename'];
				$screen_details['screen']['template_id'] = $data['template_id'];
				
				$template_id = $data['template_id'];
				
				$sql1 = "SELECT `id`, `left`, `top`, `width`, `height`, `style` FROM `field` WHERE `template_id` = $template_id";
				$res1 = sql_query($sql1);
				$i = 0;
				while($data1 = sql_row_keyed($res1, $i++)){
					$screen_details['fields'][$data1['id']]['left'] = $data1['left'];
					$screen_details['fields'][$data1['id']]['top'] = $data1['top'];
					$screen_details['fields'][$data1['id']]['width'] = $data1['width'];
					$screen_details['fields'][$data1['id']]['height'] = $data1['height'];
					$screen_details['fields'][$data1['id']]['style'] = $data1['style'];
				}
				return $screen_details;
			}
		} else {
			return false;
		}
	}
	
	function get_feed(){
		if(isset($this->screen_id) && isset($this->field_id)){
			$sql = "SELECT feed.id, field.type_id, position.last_content_id, position.range_l, position.range_h, COUNT( content.id ) as cnt
            FROM position
            LEFT JOIN field ON position.field_id = field.id
            LEFT JOIN feed ON position.feed_id = feed.id
            LEFT JOIN feed_content ON feed.id = feed_content.feed_id
            LEFT JOIN content ON feed_content.content_id = content.id
            WHERE position.screen_id =$this->screen_id
            AND field.id =$this->field_id
            AND field.type_id = content.type_id
            AND feed_content.moderation_flag =1
            AND (content.start_time < NOW() OR content.start_time IS NULL)
            AND (content.end_time > NOW() OR content.end_time IS NULL)
            GROUP BY feed.id;";
			$res = sql_query($sql);
			$i = 0;
			$range_sum = 0;
			while($data = (sql_row_keyed($res,$i++))){
			    if($data['cnt'] > 0){
				    $feeds[$data['id']]['type_id'] = $data['type_id'];
				    $feeds[$data['id']]['last_content_id'] = $data['last_content_id'];
				    $feeds[$data['id']]['low'] = floatval($data['range_l']);
				    $feeds[$data['id']]['high'] = floatval($data['range_h']);
				    $range_sum += abs($feeds[$data['id']]['high'] - $feeds[$data['id']]['low']);
				}
			}
						
			$rand = rand() * $range_sum / getrandmax();
			
			$lower_weight = 0;
			foreach($feeds as $feed_id => $feed){
			    $weight = $feed['high'] - $feed['low'];
				if($lower_weight < $rand && $rand <= $lower_weight += $weight){
					$this->feed_id = $feed_id;
					$this->type_id = $feed['type_id'];
					if(!($this->last_content_id = $feed['last_content_id']))
					    $this->last_content_id = 0;
					//echo "<br />Got it! $feed_id looks like a match!!!";
					$this->error = 0;
					return true;
				}
			}
	
			return false;

		} else {
			return false;
		}
	}
	
	function get_content(){
		if(isset($this->screen_id) && isset($this->field_id) && isset($this->feed_id)){
			$sql = "SELECT content.id FROM content
			LEFT JOIN feed_content ON content.id = feed_content.content_id
			WHERE feed_content.feed_id = $this->feed_id
			AND feed_content.moderation_flag = 1
			AND content.type_id = $this->type_id
			AND content.id > $this->last_content_id 
			AND (content.start_time < NOW() OR content.start_time IS NULL)
			AND (content.end_time > NOW() OR content.start_time IS NULL)
			ORDER BY content.id ASC
			LIMIT 1";
			$res = sql_query($sql);
			if($res!=0 && ($data = (sql_row_keyed($res,0)))){
				//echo "Found the next content";
				$this->last_content_id = $data['id'];
				$this->log_back(); //Let the system know we found this content and plan on using it
				return true;
			} else {
				$sql = "SELECT content.id FROM content
				LEFT JOIN feed_content ON content.id = feed_content.content_id
				WHERE feed_content.feed_id = $this->feed_id
				AND feed_content.moderation_flag = 1
				AND content.type_id = $this->type_id
				AND content.id > 0 
				AND (content.start_time < NOW() OR content.start_time IS NULL)
				AND (content.end_time > NOW() OR content.start_time IS NULL)
    			ORDER BY content.id ASC
				LIMIT 1"; //We loop back around to the start, 
				$res = sql_query($sql);
				if($res!=0 && ($data = (sql_row_keyed($res,0)))){
					//echo "Found a loop back";
					$this->last_content_id = $data['id'];
					$this->log_back(); //Let the system know we found this content and plan on using it
					return true;
				} else {
					//echo "No content in that feed!";
					$this->error++;
					return false;  //There was no content to be found!
				}
			}
		} else {
			return false;
		}
	}
	
	function content_details(){
	    $sql = "SELECT `template_id` FROM `screen` WHERE id = $this->screen_id LIMIT 1;";
	    $template_id = sql_query1($sql);
		$sql = "SELECT `content`, `mime_type`, `duration` FROM `content` WHERE id = $this->last_content_id;";
		$res = sql_query($sql);
		if($res!=0){
			$data = (sql_row_keyed($res,0));
			$json['content'] = stripslashes($data['content']);
			$json['mime_type'] = stripslashes($data['mime_type']);
			$json['duration'] = $data['duration'];
			
			if($data['mime_type'] == 'text/time'){ //This executes time code
				$json['mime_type'] = 'text/html';
				$json['content'] = date($data['content']);
			}
			
			$json['template_id'] = $template_id;
		
			return $json;
		} else {
			return false;
		}
	}
	
	function log_back(){
		if(isset($this->screen_id) && isset($this->field_id) && isset($this->feed_id)&& isset($this->last_content_id)){
			$sql = "UPDATE `screen` SET `last_updated` = NOW() WHERE id = $this->screen_id LIMIT 1";

			sql_command($sql);

			$sql = "UPDATE `position` SET `last_content_id` = $this->last_content_id 
			WHERE `screen_id` = $this->screen_id AND `field_id` = $this->field_id AND `feed_id` = $this->feed_id LIMIT 1;";
			
			return sql_command($sql) != -1;
		} else {
			return false;
		}
	}
}

?>
