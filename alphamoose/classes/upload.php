<?
/*
Class: Upload
Status: Working, tested for PDF, PNG and JPEG
Functionality:
Comments: 
	The goal of upload is to process/clean things up before sending them to Content to be created.
	And then clean them up after content has had a chance to play

*/
define('CONTENT_STORE','/var/www/ds-dev/bam/content/'); //Where everything is stored
//Reject Limits
define('MIN_W','400'); //Min width before we reject an image
define('MIN_H','400'); //Min height before we reject an image
//Resize Limits
define('MAX_W','1600'); //Max width before we resize an image
define('MAX_H','1200'); //Max height before we resize an image


class Uploader{
	/*How to decypher this:
	U = Sent by upload form
	C = Used by content class
	I = Internal use only
	*/
	var $name; //UC
	var $start_date; //UC
	var $end_date; //UC
	var $feeds; //U
	var $type; //C
	var $duration; //UC
	var $content_i; //U
	var $content_o; //C
	var $mime_type; //C
	var $user_id; //UC
	
	var $ctype; //UI
	var $auto; //I
	
	function __construct($name_in, $start_date_in, $end_date_in, $feeds_in, $duration_in, $content_i_in, $ctype_in, $user_id_in, $auto_in = 1){
	
		$this->name = $name_in;
		$this->start_date = $start_date_in;
		$this->end_date = $end_date_in;
		$this->duration = $duration_in;
		$this->content_i = $content_i_in;
		$this->ctype = $ctype_in;
		$this->user_id = $user_id_in;
		
		$this->feeds = $feeds_in;
		
		$this->auto = $auto_in; //This field specificies if the uploader should run in automatic mode or manual processing.  I like auto mode, but thats just me

		if($this->auto){
			$this->filer();
		} else {
			return true;
		}
	}
	//Determines which steps need to be applied to the content
	function filer(){
		if($this->ctype == 'text'){
			//Awsome, this is easy to handle!
			$this->content_o = $this->content_i;
			$this->mime_type = 'text/plain';
			$this->type_id = 2; //SELF: THIS IS BAD AND DUMB AND STUPID
			$content = new Content();
			if($content->create_content($this->name, $this->user_id, $this->content_o, $this->mime_type, $this->type_id, $this->duration, $this->start_date, $this->end_date)){
				$cid = $content->id;
				foreach($this->feeds as $fid){
					$f = new Feed($fid);
					$f->content_add($cid);
				}
				return true; //The content is finished uploading
			} else {
				return false; //Failure making a content isn't a good thing
			}
		
		} elseif($this->ctype == 'html'){
			//Awsome, this is easy to handle as well
			$this->content_o = $this->content_i;
			$this->mime_type = 'text/html';
			$this->type_id = 2; //SELF: THIS IS BAD AND DUMB AND STUPID
			$content = new Content();
			if($content->create_content($this->name, $this->user_id, $this->content_o, $this->mime_type, $this->type_id, $this->duration, $this->start_date, $this->end_date)){
				$cid = $content->id;
				foreach($this->feeds as $fid){
					$f = new Feed($fid);
					$f->content_add($cid);
				}
				return true; //The content is finished uploading
			} else {
				return false; //Failure making a content isn't a good thing
			}
		} elseif($this->ctype == 'file'){
			//echo "Identified a file upload";
			if($this->content_i['error'] == 0 && is_uploaded_file($this->content_i['tmp_name'])){
				$pre_type = $this->typer();
				
				//echo "Type: $pre_type   ";
				if($pre_type == "image/jpeg" || $pre_type == "image/pjpeg" || $pre_type == "image/jpg"){
					//echo "Bananas";
					$this->jpeg_cleaner();
				} elseif ($pre_type == "image/png"){
					$this->png_cleaner();
				} elseif ($pre_type == "application/pdf"){
					$this->pdf_cleaner(); 
				} else {
					unlink($this->content_i['tmp_name']); //Delete it since its def a virus duh!
					return false; //Unknown filetype
				}
			} else {
				return false;
			}
		} else {
			//Unknown ctype == bad
			return false;
		}
	}
	function typer(){
		//We could add enchanted MIME typing here, but for now we'll trust browsers
		return $this->content_i['type'];
	}
	function jpeg_cleaner(){
		//echo "Starting JPEG cleaner";
		$temp_dir = "/tmp/";
		$temp_name = $this->user_id . "-" . time() . ".jpg";
		$temp_dest = $temp_dir . $temp_name;
		if(move_uploaded_file($this->content_i['tmp_name'], $temp_dest)){
			chmod($temp_dest, 0644);
			//Now that we have the file and we know where it is, lets mess it up
			$src_img=imagecreatefromjpeg($temp_dest);

			$width=imageSX($src_img);
			$height=imageSY($src_img);
			//echo "Source $width x $height";
			if($width < MIN_W || $height < MIN_H){ //The image isn't big enough!
				unlink($temp_dest);
				//echo "Too Small!";
				return false;
			} elseif($width > MAX_W || $height > MAX_H){  //The image is too large, resize it!
				//echo "Too large";
				$scale_x = MAX_W / $width;
				$scale_y = MAX_H / $height;
				
				if($scale_x >= $scale_y){ //Find the dimension that needs the most help
					$scale = $scale_y;
				} else {
					$scale = $scale_x;
				}
				$new_x = $width * $scale;
				$new_y = $height * $scale;
				
				$dest_img=ImageCreateTrueColor($new_x,$new_y);
        			imagecopyresampled($dest_img,$src_img,0,0,0,0,$new_x,$new_y,$width,$height);
        			imagejpeg($dest_img, $temp_dest, 90);
        			imagedestroy($dest_img);
        			imagedestroy($src_img);
        		
        			$this->mime_type = 'image/jpeg';
        			$this->type_id = 3; //SELF: THIS IS BAD AND DUMB AND STUPID
        			//echo "But we shrunk it!";
        			return $this->mover($temp_dest);
			} else {
				$this->mime_type = 'image/jpeg';
                        	$this->type_id = 3; //SELF: THIS IS BAD AND DUMB AND STUPID
				//echo "Did not require resizing";
				return $this->mover($temp_dest);
			}

		} else {
			return false;
		}
	}
	function png_cleaner($loc = ''){
		//echo "Starting PNG cleaner";
		$temp_dir = "/tmp/";
		$temp_name = $this->user_id . "-" . time() . ".png";
		$temp_dest = $temp_dir . $temp_name;
		if($loc != ''){
			$temp_dir = $loc;
		} 
		if ($loc != '' || move_uploaded_file($this->content_i['tmp_name'], $temp_dest)){
			chmod($temp_dest, 0644);
			//Now that we have the file and we know where it is, lets mess it up
			$src_img=imagecreatefrompng($temp_dest);

			$width=imageSX($src_img);
			$height=imageSY($src_img);
			//echo "Source $width x $height";
			if($width < MIN_W || $height < MIN_H){ //The image isn't big enough!
				unlink($temp_dest);
				//echo "Too Small!";
				return false;
			} elseif($width > MAX_W || $height > MAX_H){  //The image is too large, resize it!
				//echo "Too large";
				$scale_x = MAX_W / $width;
				$scale_y = MAX_H / $height;
				
				if($scale_x >= $scale_y){ //Find the dimension that needs the most help
					$scale = $scale_y;
				} else {
					$scale = $scale_x;
				}
				$new_x = $width * $scale;
				$new_y = $height * $scale;
				
				$dest_img=ImageCreateTrueColor($new_x,$new_y);
        			imagecopyresampled($dest_img,$src_img,0,0,0,0,$new_x,$new_y,$width,$height);
        			imagepng($dest_img, $temp_dest, 90);
        			imagedestroy($dest_img);
        			imagedestroy($src_img);
        		
        			$this->mime_type = 'image/png';
        			$this->type_id = 3; //SELF: THIS IS BAD AND DUMB AND STUPID
        			//echo "But we shrunk it!";
        			return $this->mover($temp_dest);
			} else {
				$this->mime_type = 'image/png';
                        	$this->type_id = 3; //SELF: THIS IS BAD AND DUMB AND STUPID
				//echo "Did not require resizing";
				return $this->mover($temp_dest);
			}

		} else {
			return false;
		}
	}

	function pdf_cleaner(){
		$temp_dir = "/tmp/";
		$temp_name = $this->user_id . "-" . time() . ".pdf";
		$temp_dest = $temp_dir . $temp_name;
		if(move_uploaded_file($this->content_i['tmp_name'], $temp_dest)){
			$source = $temp_dest;
			$target = $temp_dir . $this->user_id . "-" . time() . ".png";
			$command = "convert " . $source . " " . $target; //This command relies on Image Magick & GS to be installed
			//echo $command;
        		exec($command);
        		unlink($source);
			$this->content_i['tmp_name'] = $target;
			$this->content_i['type'] = "image/png";
			$this->png_cleaner($target);
		} else {
		
		}
	} 
	function mover($current_loc){
		$this->content_o = $current_loc;
		$ext = substr(strrchr($current_loc, "."), 1);
		$content = new Content();
		//print_r($this);
		if($content->create_content($this->name, $this->user_id, $this->content_o, $this->mime_type, $this->type_id, $this->duration, $this->start_date, $this->end_date)){
			$cid = $content->id; 
			
			$target_loc = CONTENT_STORE . $cid . "." . $ext;
			rename($current_loc, $target_loc);
			$content->content = $cid . "." . $ext;
			$content->set_properties();
			foreach($this->feeds as $fid){
				$f = new Feed($fid);
				$f->content_add($cid);
			}
			return true; //The content is finished uploading
		} else {
			return false; //Failure making a content isn't a good thing
		}
	} 
}
?>
