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
$(function() {
	
	var api = $("#overlay").overlay({api:true});

	// define function that opens the overlay
	window.openOverlay = function() {
		api.load();
	}
});

function loadFeed(id, feedname) {
	$.ajax({
		type: "GET",
		url: "<?= ADMIN_BASE_URL ?>/wall/feedgrid/"+id+"?ajax=1",
		success: function(data){
				$('#wall_feed_insert').empty();
				$("#progressbar").progressbar({ value: 0 });
				
				var response = $(data).find('#feedgrid').html();  //Grab the div from the ajax request
				$('#wall_feed_insert').html(data);  //hide it and load some HTML
                if ($('.UIWall_image').size() <= 1)
                	total = 1;
                else
                	total = $('.UIWall_image').size() - 1;
                count = 0;
				$('.UIWall_image').each(function (i) {
                        $(this).hide();
                        $('#progressbar').show();
                        $(this).load(function() {
                                $(this).parent().css("margin-top", ($(this).parents('.UIWall_thumb').height() - $(this).height()) / 2);
                                $(this).parent().css("margin-left", ($(this).parents('.UIWall_thumb').width() - $(this).width()) / 2);
                                
                                count = count + 1;
                                $('#progressbar').progressbar('option', 'value', (count / total) * 100);
                                
                                if (count == total) {
                                    $('.UIWall_image').fadeIn();
                                    $('#progressbar').hide();
                                }
                            });
				});
		}
	});
	$(".feedsel_title").empty();
	$(".feedsel_title").append(feedname);
	collapsePanel();
}

function expandPanel() { 
	$("div#panel").css({ height: "80%" });
	$("div.panel_button").toggle();
}

function collapsePanel() { 
	$("div#panel").css({ height: "0px" });
	$("div.panel_button").toggle();
}

$(document).ready(function() {
	$("div#panel").css({ height: "0px" });
	$("div.panel_button").click(function(){
		expandPanel();
	});
	
	$("div#hide_button").click(function(){
		$("div#panel").css({ height: "0px" });
	});
	
	$(".overlayTrigger").live("click", function(e) { 
		var url = $(this).attr("href");
		$('#wrap').load(url, {ajax: 1}, function(){
			openOverlay();
		});
		e.preventDefault();
	});
});
</script>

  <div id="toppanel">
    <div id="panel">
      <div id="panel_contents"> </div>
        <div id="UIWall_feedsel">
          <?
            foreach($this->feeds as $id => $feed ) {
            $name = htmlspecialchars($feed['name']);
            if(strlen($name) > 26){
              $name = substr($name, 0, 26) . '...';
            }
          ?>
             <div class="UIWall_feedbutton" style="position:relative;"><a href="<?= ADMIN_BASE_URL ?>/wall/feedgrid/<?= $id ?>" onclick="loadFeed(<?= $id ?>, '<?= $name ?>'); return false;" title="<?= $name ?>"><div class="UIWall_contentnum"><?= $feed['count'] ?></div><?= $name ?></a></div>
          <? } ?>
            <br clear="both" />
        </div>
    </div>
    <div id="UIWall_pulldown_container">
      <div id="UIWall_pulldown">
        <div class="panel_button" style="display: visible;">
          <a href="#">
            <h1 class="feedsel_title">Click to Select Feed</h1>
            <img src="<?= ADMIN_BASE_URL ?>images/wall/pulldown_arrow.png" alt="" />
          </a>
        </div>
        <div class="panel_button" id="hide_button" style="display: none;">
          <a href="#">
            <h1 class="feedsel_title">Click to Select Feed</h1>
            <img src="<?= ADMIN_BASE_URL ?>images/wall/pullup_arrow.png" alt="" />
          </a>
        </div>
      </div>
    </div>
  </div>


<div id="wall_feed_insert">&nbsp;</div>

<div id="progressbar"></div>

<div id="overlay" class="overlay">
  <div id="wrap"></div>
</div>

<div id="bottomstrip">
	<div id="bottomstrip-padding">
		<a href="<?= ADMIN_BASE_URL ?>">&lt;&lt; Back to the Concerto Panel</a>
	</div>
</div>
