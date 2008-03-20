<?php
session_start();

include("mysql.inc");

error_reporting(0);

if(isset($_GET['id'])){
 	$sql = "SELECT s.width, s.height, t.filename, t.id, f.id, f.left, f.top, f.width, f.height, f.style FROM screen s LEFT JOIN template t ON s.template_id = t.id LEFT JOIN field f ON f.template_id = t.id WHERE s.id = {$_GET['id']} GROUP BY f.id;";
	if($res = sql_query($sql)) {
		$i = 0;
		while($row = sql_row($res,$i++)){
	        $json['screen']['width'] = $row[0];
	        $json['screen']['height'] = $row[1];
	        $json['screen']['template'] = $row[2];
	        $json['screen']['template_id'] = $row[3];
			$json['fields'][$row[4]]['left'] = $row[5];
			$json['fields'][$row[4]]['top'] = $row[6];
			$json['fields'][$row[4]]['width'] = $row[7];
			$json['fields'][$row[4]]['height'] = $row[8];
			$json['fields'][$row[4]]['style'] = $row[9];
		}
	}
} elseif(isset($_GET['screen_id']) && isset($_GET['field_id'])) {
 	$sql = "SELECT c.content, c.mime_type, c.duration, s.template_id FROM position p LEFT JOIN screen s ON s.id = p.screen_id LEFT JOIN field fl ON p.field_id = fl.id LEFT JOIN feed f ON p.feed_id = f.id LEFT JOIN feed_content fc ON f.id = fc.feed_id LEFT JOIN content c ON fc.content_id = c.id WHERE s.id = {$_GET['screen_id']} AND fl.id = {$_GET['field_id']} AND fl.type_id = c.type_id AND fc.moderation_flag =1 AND (c.start_time < NOW() OR c.start_time IS NULL) AND (c.end_time > NOW() OR c.end_time IS NULL) GROUP BY c.id;";
 	if($res = sql_query($sql)) {
 		$_SESSION['content'][$_GET['field_id']] = ++$_SESSION['content'][$_GET['field_id']] % sql_count($res);
 		$row = sql_row($res, $_SESSION['content'][$_GET['field_id']]);
 		$json['content'] = $row[0];
 		$json['mime_type'] = $row[1];
 		$json['duration'] = $row[2];
 		$json['template_id'] = $row[3];
 		if($json['mime_type'] == 'text/time'){
 		    $json['mime_type'] = 'text/html';
 		    $json['content'] = date($json['content']);
 		}
 	}
}
	
echo json_encode($json);
?>
