<?php
if(!isset($_GET["file"]) or !isset($_GET["width"]) or !isset($_GET["height"])){
	$new_image = imagecreatetruecolor(100, 100);
	die;
}else{
	$filename = $_GET["file"];
	$new_width = $_GET["width"];
	$new_height = $_GET["height"];

	list($width, $height) = getimagesize($filename);

	$ratio = $width / $height;
	$new_ratio = $new_width / $new_height;

	if($ratio < $new_ratio) {
		$new_height = $new_height;
		$new_width = $new_height * $ratio;
	} else {
		$new_width = $new_width;
		$new_height = $new_width / $ratio;
	}

	$new_image = imagecreatetruecolor($new_width, $new_height);
	$image = imagecreatefromjpeg($filename);
	imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
}

header('Content-type: image/jpeg');
imagejpeg($new_image, NULL, 100);
imagedestroy($new_image);
?>

