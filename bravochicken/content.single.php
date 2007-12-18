<?php
session_start();

include("mysql.inc");

if(isset($_GET['mac'])) {
 	$sql = "SELECT s.id, s.vertical, t.filename, t.stylesheet, f.id, f.tag FROM screen s LEFT JOIN template t ON s.template_id = t.id LEFT JOIN field f ON f.template_id = t.id WHERE s.mac_address = {$_GET['mac']} GROUP BY f.id;";
	$res = sql_query($sql);
	if($res) {
		$i = 0;
		while($row = sql_row($res,$i++)){
			$json['screen'] = $row[0];
			$json['height'] = $row[1];
			$json['template'] = $row[2];
			$json['stylesheet'] = $row[3];
		}
	}
} elseif(isset($_GET['screen_id'])) {
	if(!isset($_SESSION['fields'])) {
	 	$sql = "SELECT f.id, f.tag FROM screen s LEFT JOIN template t ON s.template_id = t.id LEFT JOIN field f ON f.template_id = t.id WHERE s.id = {$_GET['screen_id']} GROUP BY f.id;";
		$res = sql_query($sql);
		if($res) {
			for($i=0; $row = sql_row($res,$i); ++$i){
				$_SESSION['fields'][$row[0]]['tag'] = $row[1];
			}
		}		
	}
	
	foreach($_SESSION['fields'] as $field_id => &$field_data) {
		if(!isset($field_data['timeout']) or $field_data['timeout'] < time()) {
			$sql = "SELECT c.content, c.mime_type, c.duration FROM position p LEFT JOIN field fl ON p.field_id = fl.id LEFT JOIN feed f ON p.feed_id = f.id LEFT JOIN feed_content fc ON f.id = fc.feed_id LEFT JOIN content c ON fc.content_id = c.id WHERE p.screen_id = {$_GET['screen_id']} AND fl.id = $field_id AND fl.type_id = c.type_id AND fc.moderation_flag =1 AND (c.start_time > NOW() OR c.start_time IS NULL) AND (c.end_time < NOW() OR c.end_time IS NULL) GROUP BY c.id;";
		 	$res = sql_query($sql);
		 	if($res) {
		 		$field_data['current_content_id'] = ++$field_data['current_content_id'] % sql_count($res);
		 		$row = sql_row($res, $field_data['current_content_id']);
		 		$json[$field_data['tag']]['content'] = $row[0];
		 		$json[$field_data['tag']]['mime_type'] = $row[1];
		 		$field_data['timeout'] = time() + (int)($row[2] / 1000) + 1;
		 	}
		}
	}
}
	
echo json_encode($json);
?>
