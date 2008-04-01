<?

function render($type, $filename, $width = false, $height = false, $stretch = false){
	$fileinfo = split("\.", $filename);
	if($type == 'image'){
		$cache_path = IMAGE_DIR . 'cache/' . $fileinfo[0] . '_' . $width . '_' . $height . '.' . $fileinfo[1];
		$path = IMAGE_DIR . $filename;
	} elseif ($type == 'template'){
		$cache_path = TEMPLATE_DIR . 'cache/' . $fileinfo[0] . '_' . $width . '_' . $height . '.' . $fileinfo[1];
		$path = TEMPLATE_DIR . $filename;
	}
	if($width && $height){
		if(file_exists($cache_path) && $size = getimagesize($cache_path)){ //Do we already have a cached copy at the ready?
			$fp = fopen($cache_path, "rb");
			header("Content-type: {$size['mime']}");
			fpassthru($fp); //If so, then serve it
			exit(0);
		} else { 
			include_once(COMMON_DIR.'image.inc.php');
			resize($path, $width, $height, $stretch); //If not, lets resize it.
			
			$log_file = CONTENT_DIR . 'render/render_log';
			if($fh = @fopen($log_file, 'a')){
				$log_data = $path . ' ' . $width . ' ' . $height . "\n";
				echo $log_data;
				fwrite($fh, $log_data);
				fclose($fh);
			}
			exit(0);
		}
	} else {
		include_once(COMMON_DIR.'image.inc.php');
		resize($path);
		exit(0);
	}
}

function cache_parse($threshold = 100){
	$log_file = CONTENT_DIR . 'render/render_log';
	if($fh = @fopen($log_file, 'r')){
		while (!feof($fh)){
			$line = fgets($fh);
			$line = str_replace("\n", "", $line);
			$line_data = split(' ', $line);
			if(count($line_data) == 3){
				if(isset($data[$line_data[0]][$line_data[1]][$line_data[2]])){
					$data[$line_data[0]][$line_data[1]][$line_data[2]]++; //Count how many times that content/width/height set apperas
				} else {
					$data[$line_data[0]][$line_data[1]][$line_data[2]] = 1;
				}
			}
		}
		fclose($fh);
		unset($fh);
	}
	if(!isset($data)){ //Nothing has been viewed/logged.  Do no more thinking
		return true;
	}
	//Now we find those entries that are worthy of a cache
	foreach($data as $file_path => $details){
		foreach($details as $width => $h_details){
			foreach($h_details as $height => $count){
				if($count > $threshold){ // The content has been called enough we should cache it
					generate_cache($file_path, $width, $height);
				}
			}
		}
	}
	//Then empty the log file
	@system("rm $log_file");
}
function generate_cache($filename, $width, $height){
	$path = split('/', $filename);

	$file = end($path);
	$fileinfo = split("\.", $file);
	$type = $path[count($path)-2];
	
	if($type == 'images'){
		$cache_path = IMAGE_DIR . 'cache/' . $fileinfo[0] . '_' . $width . '_' . $height . '.' . $fileinfo[1];
	} elseif ($type == 'templates'){
		$cache_path = TEMPLATE_DIR . 'cache/' . $fileinfo[0] . '_' . $width . '_' . $height . '.' . $fileinfo[1];
	}
	if(!file_exists($cache_path)){ //Maybe we already got it and the log it out of date
		$new_width = $width;
		$new_height = $height;
		
		list($old_width, $old_height) = getimagesize($filename);
        $old_ratio = $old_width / $old_height;
        $new_ratio = $new_width / $new_height;

        if($old_ratio < $new_ratio) {
            $new_height = $new_height;
            $new_width = $new_height * $old_ratio;
        } else {
            $new_width = $new_width;
            $new_height = $new_width / $old_ratio;
        }

		$new_image = imagecreatetruecolor($new_width, $new_height);
		
		$image = @imagecreatefromjpeg($filename) or //Read JPEG
		$image = @imagecreatefrompng($filename) or //Read PNG
		$image = @imagecreatefromgif($filename) or //Read GIF
		$image = false;

		if($image) {
			imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
			imagedestroy($image);
			if($fileinfo[1] == 'jpg' || $fileinfo[1] == 'jpeg'){
				imagejpeg($new_image, $cache_path, 80);
			} elseif($fileinfo[1] == 'png'){
				imagepng($new_image, $cache_path, 1);
			} elseif($fileinfo[1] == 'gif'){
				imagegif($new_image, $cache_path);
			}
			imagedestroy($new_image);
		}
	}
}

function clear_cache($dirname){
	$dir = opendir($dirname);
    while ($file = readdir($dir)){
            if (($file != ".") && ($file != "..") && !is_dir($dirname . $file)){
                    unlink($dirname . $file);
            }

    }
    closedir($dir);
}
?>
