<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technolgies Group)
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
 * @author       Web Technologies Group, $Author: zaik $
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision: 543 $
 */
?>

<script type="text/javascript">
$(document).ready(function() {
	$("a.showhide").click(function(){
		$("div#panel").animate({
			height: "550px"
		})
		.animate({
			height: "525px"
		}, "fast");
		$("a.showhide").toggle();
	
	});	
	
   $("a#hide_button").click(function(){
		$("div#panel").animate({
			height: "0px"
		}, "fast");
		
	
   });	
	
});
</script>

  <div id="toppanel">
    <div id="panel">
      <div id="panel_contents"> </div>
			<div id="UIWall_feedsel">
				<?php 
					$allfeeds_xml = "http://concerto.rpi.edu/content/render/?select=system";
								
					$objDOM = new DOMDocument();
				  $objDOM->load($allfeeds_xml);
				
				  $feeds = $objDOM->getElementsByTagName("feed");
				  // for each feed tag, parse the document and get values for
				  // id and name tags
		
				  foreach( $feeds as $value )
				  {
				    $ids = $value->getElementsByTagName("id");
				   	$id  = $ids->item(0)->nodeValue;
				    $names = $value->getElementsByTagName("name");
		    		$name  = $names->item(0)->nodeValue;
				 ?>
				 		<div class="UIWall_feedbutton"><a href="<?= ADMIN_BASE_URL ?>/wall/feedgrid?feed_id=<?= $id ?>" alt="" /><?= substr($name, 0, 26); ?><? if (strlen($name) > 26) { ?>...<? } ?></a></div>
				 <?php
				  }
				 ?>
			</div>
    </div>
    <div id="UIWall_pulldown_container">
			<div id="UIWall_pulldown">
				<a class="showhide" href="#">
					<h1>Currently selected: </h1>
					<img src="<?= ADMIN_BASE_URL ?>images/wall/pulldown_arrow.png" alt="" />
				</a>
				<a class="showhide" id="hide_button" style="display:none;" href="#">
					<h1>Currently selected: </h1>
					<img src="<?= ADMIN_BASE_URL ?>images/wall/pullup_arrow.png" alt="" />
				</a>
			</div>
		</div>

  </div>



<div id="wall_feed_insert">
</div>


