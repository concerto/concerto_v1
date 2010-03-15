<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technologies Group)
 *
 * This program is free software; you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.  You should have received a copy
 * of the GNU General Public License along with this program.
 *
 * @package      Concerto
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */
function resize($filename, $new_width = false, $new_height = false, $stretch = false){
    list($width, $height, $type_int) = getimagesize($filename);
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

    $type = image_type_to_mime_type($type_int);
    
    //If the image is a PNG, make sure the alpha layer is respected!
    if($image && ($type == 'image/png' || $type == 'image/x-png')){
      $alpha = imagecolortransparent($image);
      if($alpha >= 0){
        $color = imagecolorsforindex($image, $alpha);
        $alpha = imagecolorallocate($new_image, $color['red'], $color['green'], $color['blue']);
        imagefill($new_image, 0, 0, $alpha);
        imagecolortransparent($new_image, $alpha);
      } else {
        imagealphablending($new_image, false);
        $color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
        imagefill($new_image, 0, 0, $color);
        imagesavealpha($new_image, true);
      }
      
    }
    
    if($image) {
	    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagedestroy($image);
    } else
        $new_image = imagecreatetruecolor(3, 3);
        
    
    if($type == "image/jpeg" || $type == 'image/pjpeg' || $type == 'image/jpg'){
      header('Content-type: image/jpeg');
      $type = 'jpeg';
      imagejpeg($new_image, NULL, 100);
    }elseif($type == 'image/png' || $type == 'image/x-png'){
      header('Content-type: image/png');
      $type = 'png';
      imagepng($new_image);
    }elseif($type == 'image/gif'){
      header('Content-type: image/gif');
      $type = 'gif';
      imagegif($new_image);
    }else{
      //JPEG is default case
      header('Content-type: image/jpeg');
      $type='jpeg';
      imagejpeg($new_image, NULL, 100);
    }
    imagedestroy($new_image);
    return $type;
}
?>
