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

function loadFeed(id, feedname) {
	$.ajax({
		type: "GET",
		url: "<?= ADMIN_BASE_URL ?>/wall/feedgrid?feed_id="+id+"",
		success: function(data){
				$('#wall_feed_insert').empty();
				var response = $(data).find('#feedgrid').html(); 							//Grab the div from the ajax request
				$('#wall_feed_insert').hide().html(response).fadeIn();		//fade it into the div on this page
		}
	});
	$("#feedsel_title").empty();
	$("#feedsel_title").append(feedname);
	collapsePanel();
}

function expandPanel() { 
	$("div#panel").animate({ height: document.documentElement.clientHeight * 0.8 }).animate({ height: document.documentElement.clientHeight * 0.8 - 25 }, "fast");
	$("div.panel_button").toggle();
}

function collapsePanel() { 
	$("div#panel").animate({ height: "0px" }, "fast");
	$("div.panel_button").toggle();
}

$(document).ready(function() {
	$("div#panel").css({ height: "0px" });
	$("div.panel_button").click(function(){
		expandPanel();
	});	
	
  $("div#hide_button").click(function(){
		$("div#panel").animate({ height: "0px" }, "fast");
  });
  
  $(".lf_button").live("click", function(e) { 
  	var trigger = this.getTrigger();
  	console.log(trigger.attr("href"));
  	loadFeed(trigger.attr("href"));
  	e.preventDefault();
  	
  });
  
  // if the function argument is given to overlay, it is assumed to be the onBeforeLoad event listener 
  $("a[rel]").overlay(function() {  
	  // grab wrapper element inside content 
	  var wrap = this.getContent().find("div#wrap"); 
		var timer;
		var trigger = this.getTrigger();
	  timer = setTimeout(function() {
	  		wrap.load(trigger.attr("href"));
	 	}, 300);
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
				 		<div class="UIWall_feedbutton"><a href="javascript:loadFeed(<?= $id ?>, '<?= $name ?>')" alt="" /><?= substr($name, 0, 26); ?><? if (strlen($name) > 26) { ?>...<? } ?></a></div>
				 <?php
				}
				?>
				<div style="clear:both;"></div>
			</div>
    </div>
    <div id="UIWall_pulldown_container">
			<div id="UIWall_pulldown">
				<div class="panel_button" style="display: visible;">
      		<a href="#">
      			<h1 id="feedsel_title">Click to Select Feed</h1>
	      		<img src="<?= ADMIN_BASE_URL ?>images/wall/pulldown_arrow.png" alt="" />
	      	</a>
      	</div>
    		<div class="panel_button" id="hide_button" style="display: none;">
					<a href="#">
						<h1 id="feedsel_title">Click to Select Feed</h1>
						<img src="<?= ADMIN_BASE_URL ?>images/wall/pullup_arrow.png" alt="" />
					</a>
      	</div>

			</div>
		</div>

  </div>


<div id="wall_feed_insert">&nbsp;</div>

<div id="bottomstrip">
	<div id="bottomstrip-padding">
		<a href="<?= ADMIN_BASE_URL ?>"><< Back to the Concerto Panel</a>
	</div>
</div>