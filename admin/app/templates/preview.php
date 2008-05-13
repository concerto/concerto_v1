<?php
/*
 * creates a thumbnail of a screen with overlays
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

   $box_color=imagecolorallocatealpha($new_image, 100, 100, 100, 64);
   $box_color2=imagecolorallocatealpha($new_image, 75, 75, 75, 40);
   $text_color=imagecolorallocate($new_image, 255,255,255);
   $font_size=$new_height/14;
   $font=COMMON_DIR.'FreeSans.ttf';

   foreach ($this->fields as $field) {
	//echo $field['id'].'.'.$this->act_field.' ';
	if($field['id']==$this->act_field) {
		imagefilledrectangle($new_image,$new_width*$field['left'],$new_height*$field['top'],
				$new_width*($field['left']+$field['width']),$new_height*($field['top']+$field['height']),$box_color2);
		imagerectangle($new_image,$new_width*$field['left'],$new_height*$field['top'],
				$new_width*($field['left']+$field['width']),$new_height*($field['top']+$field['height']),$text_color);
	} else{

		imagefilledrectangle($new_image,$new_width*$field['left'],$new_height*$field['top'],
				$new_width*($field['left']+$field['width']),$new_height*($field['top']+$field['height']),$box_color);
	}
	$tbox = imageTTFBBox ($font_size,0,$font,$field['name']);
	imageTTFText($new_image,$font_size,0,
		$new_width*($field['left']+$field['width']/2)-($tbox[2]-$tbox[0])/2,
		$new_height*($field['top']+$field['height']/2)-($tbox[5]-$tbox[1])/2,
		$text_color,$font,$field['name']);

	$theight = $tbox[1];
        $twidth= $tbox[2];
   }
   
}

header('Content-type: image/jpeg');
imagejpeg($new_image, NULL, 100);
imagedestroy($new_image);
exit();
?>
