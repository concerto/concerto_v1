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
			if($fh = fopen($log_file, 'a')){
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
?>