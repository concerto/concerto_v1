<?php
session_start();

$_SESSION['count']=(int)rand()*3;

if($_SESSION['count']%3==0)
	$var['content']="Ballz";
elseif($_SESSION['count']%3==1)
	$var['content']="Titties";
else
	$var['content']="Saggy Titties";
	
$var['duration']=3000;
	
echo json_encode($var);
?>
