<?php
function resize($filename, $new_width = false, $new_height = false, $stretch = false){
    list($width, $height) = getimagesize($filename);
    if(!$stretch) {
        if(!$new_width || !$new_height) {
	        $new_width = $width;
	        $new_height = $height;
        } else {
            $ratio = $width / $height;
            $new_ratio = $new_width / $new_height;

            if($ratio < $new_ratio) {
                $new_height = $new_height;
                $new_width = $new_height * $ratio;
            } else {
                $new_width = $new_width;
                $new_height = $new_width / $ratio;
            }
        }
    }

    $new_image = imagecreatetruecolor($new_width, $new_height);
    $image = @imagecreatefromjpeg($filename) or //Read JPEG
    $image = @imagecreatefrompng($filename) or //Read PNG
    $image = @imagecreatefromgif($filename) or //Read GIF
    $image = false;

    if($image) {
	    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagedestroy($image);
    } else
        $new_image = imagecreatetruecolor(100, 100);

    header('Content-type: image/jpeg');
    imagejpeg($new_image, NULL, 100);
    imagedestroy($new_image);
    exit(0);
}
?>
