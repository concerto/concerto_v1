<div id="wallthumbs">
		<?php
				$feed_id = $_GET['feed_id'];
				$jsondata = file_get_contents(ROOT_URL_ABSOLUTE . "admin/includes/feedjson.php?feedid=" . $feed_id, FILE_USE_INCLUDE_PATH);
				$feeddata = json_decode($jsondata);
				$count = 0;
				foreach ($feeddata as $obj) { 
						$feed_data = $obj->{'feed'};
						$feed_name = rawurlencode($feed_data[0]->{'name'});
		?>
							  <div class="UIWall_thumb"><a class="overlayTrigger" href="<?= ADMIN_URL ?>/wall/ext?content_id=<?= $obj->{'id'} ?>&amp;feed_name=<?= $feed_name ?>" rel="#oz"><div class="UIWall_wrapper"><img src="<?= $obj->{'content'} ?>" alt="" /></div></a></div>
		  
		<?php
		}
?>
</div>

<div id="oz" class="overlayZoom">
		<div id="wrap"></div>
</div>
