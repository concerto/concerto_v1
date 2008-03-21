<?php
/*
 * image view fetches and sends a resized (or not) image for use in the admin panel
 * essentially taken fron bravochicken's 
 */
if(!isset($this->file)){
   $new_image = imagecreatetruecolor(100, 100);
   die;
}else{
   $filename = $this->file;
   $new_width = $this->width;
   $new_height = $this->height;

   list($width, $height) = getimagesize($filename);

   if(!isset($new_width) || !isset($new_height)){
      $new_width = $width;
      $new_height = $height;
   }

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