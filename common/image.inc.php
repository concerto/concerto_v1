<?php
function resize($filename, $new_width, $new_height){
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
    $image = imagecreatefromjpeg($filename) or //Read JPEG
        $image = imagecreatefrompng($filename) or //Read PNG
        $image = imagecreatefromgif($filename) or //Read GIF
        $image = false;
    if($image)
	    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    else
        $new_image = imagecreatetruecolor(100, 100);

    header('Content-type: image/jpeg');
    imagejpeg($new_image, NULL, 100);
    imagedestroy($new_image);
    if($image)
        imagedestroy($image);
    exit(0);
}
?>
