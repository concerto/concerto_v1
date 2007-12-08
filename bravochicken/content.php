<?php
session_start();

if($_GET['id']==1) {
	$var['content']="Ballz";
	$var['mime-type']="text/plain";
} elseif($_GET['id']==2) {
	$var['content']="Titties";
	$var['mime-type']="text/plain";
} else {
	$var['content']="images/turkey.jpg";
	$var['mime-type']="image/jpeg";
}
	
$var['duration']=3000;
	
echo json_encode($var);
?>
