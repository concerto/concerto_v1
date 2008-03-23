<?
/*
Class: Notification
Status: New
Functionality:  
Comments:		
*/
class Notification{
	
	function __construct(){
	}
	function notify($type_in, $type_id_in, $by_in, $by_id_in, $msg_in){
		$sql = "INSERT INTO `notifications` (`type`, `type_id`, `by_type`, `by_id`, `msg`, `timestamp`) 
		VALUES ('$type_in', $type_id_in, '$by_in', $by_id_in, '$msg_in', NOW())";
		$res = sql_query($sql);
	}
}
?>
