<?
function go_mail(){

	$username = 'rpi@concerto-signage.com';
	$password = '216719156';

	$mbox = imap_open ("{imap.gmail.com:993/imap/ssl}INBOX", $username, $password) or die("can't connect: " . imap_last_error());

	$count = imap_num_msg($mbox);
	for($i = 1; $i<=$count; $i++){
		$headers = imap_header($mbox,$i);
		$to = $headers->to[0]->mailbox;

		//The following it designed to match an address like rpi+g-123
		preg_match('/(\w+)\+(\w)\-(\w+)/', $to, $res);
		$main = $res[1]; //Not used
		$type = $res[2];
		$id = $res[3];

		$subject = $headers->subject;
		$from = $headers->fromaddress;
		print_r(imap_fetchstructure($mbox, $i));
		$body = strip_tags(imap_fetchbody($mbox, $i, 0));
		echo $body;
		if($type == 'g1'){
			$obj = new Group($id);
			if(!$obj->set || !$obj->id){
				echo "Group $id doesnt exist.  Mail is junk.\n";
				return false;
			} else {
				echo "Shooting an email from $from to $obj->name about $subject\n";
				//$obj->send_mail($subject, $body, $from, true);
			}
		}elseif($type == 'u'){
	                $obj = new User($id);
	                if(!$obj->set || !$obj->id){
	                        echo "User $id doesnt exist. Mail is junk.\n";
	                        return false;
	                } else {
				echo "Shooting an email from $from to $obj->name about $subject\n";
				//$obj->send_mail($subject, $body, $from, true);
	                }
	        }
//		imap_delete($mbox, $i);
	}
//	imap_expunge($mbox);
	imap_close($mbox);
}


function get_mime_type(&$structure) {
   $primary_mime_type = array("TEXT", "MULTIPART","MESSAGE", "APPLICATION", "AUDIO","IMAGE", "VIDEO", "OTHER");
   if($structure->subtype) {
   	return $primary_mime_type[(int) $structure->type] . '/' .$structure->subtype;
   }
   	return "TEXT/PLAIN";
}

function get_part($stream, $msg_number, $mime_type, $structure = false,$part_number    = false) {
   
   	if(!$structure) {
   		$structure = imap_fetchstructure($stream, $msg_number);
   	}
   	if($structure) {
   		if($mime_type == get_mime_type($structure)) {
   			if(!$part_number) {
   				$part_number = "1";
   			}
   			$text = imap_fetchbody($stream, $msg_number, $part_number);
   			if($structure->encoding == 3) {
   				return imap_base64($text);
   			} else if($structure->encoding == 4) {
   				return imap_qprint($text);
   			} else {
   			return $text;
   		}
   	}
   
		if($structure->type == 1) /* multipart */ {
   		while(list($index, $sub_structure) = each($structure->parts)) {
   			if($part_number) {
   				$prefix = $part_number . '.';
   			}
   			$data = get_part($stream, $msg_number, $mime_type, $sub_structure,$prefix .    ($index + 1));
   			if($data) {
   				return $data;
   			}
   		} // END OF WHILE
   		} // END OF MULTIPART
   	} // END OF STRUTURE
   	return false;
   } // END OF FUNCTION
   

?>
