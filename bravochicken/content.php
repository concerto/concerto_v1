<?php
session_start();

include("mysql.inc");

if(isset($_GET['mac'])) {
 	$sql = "SELECT s.id, t.filename, t.stylesheet, s.vertical, f.id, f.tag FROM screen s LEFT JOIN template t ON s.template_id = t.id LEFT JOIN field f ON f.template_id = t.id WHERE s.mac_address = {$_GET['mac']} GROUP BY f.id;";
	$res = sql_query($sql);
	if($res) {
		$i = 0;
		while($row = sql_row($res,$i++)){
			$json['screen'] = $row[0];
			$json['template'] = $row[1];
			$json['attr']['stylesheet'] = $row[2];
			$json['attr']['height'] = $row[3];
			$json['fields'][$row[4]] = $row[5];
		}
	}
} elseif(isset($_GET['screen_id']) && isset($_GET['field_id'])) {
 	$sql = "SELECT c.content, c.mime_type, c.duration FROM position p LEFT JOIN field fl ON p.field_id = fl.id LEFT JOIN feed f ON p.feed_id = f.id LEFT JOIN feed_content fc ON f.id = fc.feed_id LEFT JOIN content c ON fc.content_id = c.id WHERE p.screen_id = {$_GET['screen_id']} AND fl.id = {$_GET['field_id']} AND fl.type_id = c.type_id AND fc.moderation_flag =1 AND (c.start_time > NOW() OR c.start_time IS NULL) AND (c.end_time < NOW() OR c.end_time IS NULL) GROUP BY c.id;";
 	$res = sql_query($sql);
 	if($res) {
 		$_SESSION['content'][$_GET['field_id']] = ++$_SESSION['content'][$_GET['field_id']] % sql_count($res);
 		$row = sql_row($res, $_SESSION['content'][$_GET['field_id']]);
 		$json['content'] = $row[0];
 		$json['mime_type'] = $row[1];
 		$json['duration'] = $row[2];
 	}
}
	
echo json_encode($json);
?>
