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
	var $mac_id;
	var $screen_id;
	var $field_id;
	var $type_id;
	var $feed_id;
	var $content_id;
	
	var $set;
	var $feeds;
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
			if($this->error >= 1){ //but of course all might not be well in the database land.. so we think about those problems here
				//echo "Hitting the advanced code";
				//The majority of this code is to address "empty" feeds.
				//The following code builds an of feeds that have content in them
				$sql = "SELECT COUNT(content.id) as content_count, feed_id FROM content
				LEFT JOIN feed_content ON content.id = feed_content.content_id
				WHERE feed_content.moderation_flag = 1
				AND content.type_id = $this->type_id
				AND (content.start_time < NOW() OR content.start_time IS NULL)
				AND (content.end_time > NOW() OR content.start_time IS NULL)
				GROUP BY feed_id";
				$res = sql_query($sql);
				$i = 0;
				while($data = (sql_row_keyed($res,$i++))){
					$this->feeds[] = $data['feed_id']; 
				}
				//echo "<br />We just found the feeds with content, they are:"; print_r($this->feeds);
		
				//Now we test to see how many of those feeds are actually mapped to this position
				$feed_string = implode (',', $this->feeds);
				$sql = "SELECT `feed_id`, `range_l`, `range_h` FROM `position` 
				WHERE `screen_id` = $this->screen_id AND `field_id` = $this->field_id AND feed_id IN ($feed_string) ORDER BY `range_l` ASC";
				$res = sql_query($sql);
				$j = 0;
				$range_sum = 0;
				while($data = (sql_row_keyed($res,$j))){
					$feed_range[$data['feed_id']]['range'] = $data['range_h'] - $data['range_l'];
					$range_sum = $range_sum + $feed_range[$data['feed_id']]['range'];
					$j++;
				}
				//echo "<br /> Just calculated ranges:"; print_r($feed_range);
				$scale = 1/$range_sum;
				$rand = rand(0,100) / 100;
				$base = 0;
				foreach($feed_range as $feed_id => $feed){
					$feed['low'] = $base;
					$feed['high'] = $base + $feed['range'] * $scale;
					$base = $feed['high'];
					//print_r($feed);
					if($feed['low'] < $rand && $rand <= $feed['high']){
						$this->feed_id = $feed_id;
						//echo "<br />Got it! $feed_id looks like a match!!!";
						$this->error = 0;
						return true;
					}
				}
		
				return false;
				
			} else { //We start off by assuming that all is well in the Concerto world, there is plenty of content to be bad
				//The "regular" weighting algorithm
				$rand = rand(0,100) / 100;
				$sql = "SELECT `feed_id`, `content_id` FROM `position` WHERE `screen_id` = $this->screen_id AND `field_id` = $this->field_id AND 
				(`range_l` < $rand AND $rand <= `range_h`)";
				$res = sql_query($sql);
				if($res != 0){
					$data = (sql_row_keyed($res,0));
					$this->feed_id = $data['feed_id'];
					$this->content_id = $data['content_id'];
					return true;
				} else {
					$this->error++;
					return false;
				}
			}
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
			AND content.id > $this->content_id 
			AND (content.start_time < NOW() OR content.start_time IS NULL)
			AND (content.end_time > NOW() OR content.start_time IS NULL)
			LIMIT 1";
			$res = sql_query($sql);
			if($res!=0 && ($data = (sql_row_keyed($res,0)))){
				//echo "Found the next content";
				$this->content_id = $data['id'];
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
				LIMIT 1"; //We loop back around to the start, 
				$res = sql_query($sql);
				if($res!=0 && ($data = (sql_row_keyed($res,0)))){
					//echo "Found a loop back";
					$this->content_id = $data['id'];
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
		$sql = "SELECT `content`, `mime_type`, `duration` FROM `content` WHERE id = $this->content_id;";
		$res = sql_query($sql);
		if($res!=0){
			$data = (sql_row_keyed($res,0));
			$data['content'] = stripslashes($data['content']);
			$data['mime_type'] = stripslashes($data['mime_type']);
			
			if($data['mime_type'] == 'text/time'){ //This executes time code
				$data['mime_type'] = 'text/html';
				$data['content'] = date($data['content']);
			}
			
			$data['template_id'] = $template_id;
		
			return $data;
		} else {
			return false;
		}
	}
	
	function log_back(){
		if(isset($this->screen_id) && isset($this->field_id) && isset($this->feed_id)&& isset($this->content_id)){
			$sql = "UPDATE `position` SET `content_id` = $this->content_id 
			WHERE `screen_id` = $this->screen_id AND `field_id` = $this->field_id AND `feed_id` = $this->feed_id";
			sql_query($sql);
			return true;
		} else {
			return false;
		}
	}
}

?>
