<?php
session_start();

if($_GET['id']==1) {
	$var['content']="Ballz";
	$var['mime-type']="text/plain";
} elseif($_GET['id']==2) {
	$var['content']="Mysterious mammal caught on film — An 'extraordinary' desert creature has been caught on camera for what scientists believe is the first time.";
	$var['mime-type']="text/plain";
} else {
	$var['content']="images/turkey.jpg";
	$var['mime-type']="image/jpeg";
}
	
$var['duration']=5000;
	
echo json_encode($var);
?>
