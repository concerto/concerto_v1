<script type="text/javascript"><!--
(function($) {
    $.fn.extend({
        expand_details: function(){
            if($(this).data("loaded") == undefined) {
                $(this).data("loaded", 0);
                var parent = this;
                $.ajax({type: "POST",
                        url: "<?=ADMIN_URL?>/browse/details",
                        data: {"content_id": $(parent).attr("id").replace(/c/,""),
                               "feed_id": <?=$this->feed->id?>
                        },
                        success: function(html){
                            $("<tr>").attr("class", "details")
                            .append($("<td colspan=5>").html(html))
                            .hide()
                            .insertAfter(parent)
                            .find("#i-preview").lightBox({
                                overlayBgColor: "#000",
                                imageLoading: "<?=ADMIN_BASE_URL?>images/lightbox-ico-loading.gif",
                                imageBtnClose: "<?=ADMIN_BASE_URL?>images/lightbox-btn-close.gif"
                            });
                            $(parent).data("loaded", 1).expand_details();
                        },
                        dataType: "html"
                });
            } else if($(this).data("loaded") == 1) {
                $(this).addClass("listitem_sel").next().fadeIn("slow", function(){$(this).data("visible", true);});
            }
        },

        collapse_details: function(){
            if($(this).data("loaded") == 1) {
                $(this).removeClass("listitem_sel").next().fadeOut("slow", function(){$(this).data("visible", false);});
            }
        },

        toggle_details: function(){
            if($(this).next().data("visible")) {
                $(this).collapse_details();
            } else {
                $(this).expand_details();
            }
        }
    });

    $(document).ready(function() {
        $("table.content_listing").tablesorter({
            sortList: [[1,0]],
            headers: {
                0: {sorter: false}
            },
            textExtraction: function(obj) {
                if($(obj).attr("class") == "listtitle")
                    return $(obj).children(0).html();
                else
                    return $(obj).html();
            }
        }).bind("sortStart",function() {
            $(".details").remove();
            $(".listitem").removeData("loaded");
        });

        $(".listitem").click(function() {
            $(this).toggle_details();
            return false;
        });

        $("#expandall").click(function() {
            $(".listitem").each(function(){$(this).expand_details();});
            return false;
        });

        $("#collapseall").click(function() {
            $(".listitem").each(function(){$(this).collapse_details();});
            return false;
        });
    });
})(jQuery);
//--></script>
<?
if(($this->feed->type == 4) && ($this->feed->dyn->needs_update() > 0)){
?>
<p class="dyn_stat"><b>Currently Processing:</b>&nbsp;&nbsp;&nbsp;This dynamic feed has <?=$this->feed->dyn->needs_update()?> unprocessed item(s).  It should be ready within a couple minutes.</p>
<?
}
?>


<div style="float:left; width:70%;">
	<ul id="maintab" class="ui-tabs-nav">
		<li class="first<?php if(!isset($this->args[4])) { ?> ui-tabs-selected<?php } ?>"><a class="approved" href="<?= ADMIN_URL ?>/browse/show/<?= $this->feed->id ?>/type/<?= $this->type_id ?>"><h1>Active</h1></a></li>
		<li class="<?php if($this->feed->user_priv($_SESSION['user'], "moderate")) { ?>middle<?php } else { ?>last<?php } ?><?php if($this->args[4] == "expired") { ?> ui-tabs-selected<?php } ?>"><a class="expired" href="<?= ADMIN_URL ?>/browse/show/<?= $this->feed->id ?>/type/<?= $this->type_id ?>/expired"><h1>Expired</h1></a></li>
<?php if($this->feed->user_priv($_SESSION['user'], "moderate")) { ?>
		<li class="last<?php if($this->args[4] == "declined") { ?> ui-tabs-selected<?php } ?>"><a class="denied" href="<?= ADMIN_URL ?>/browse/show/<?= $this->feed->id ?>/type/<?= $this->type_id ?>/declined"><h1>Declined</h1></a></li>
<?php } ?>
	</ul>

</div>
<div style="float:right:width:30%;padding:25px 15px 0px 0px; text-align:right;">
  
    <a id="expandall" href="#">Expand All</a> | <a id="collapseall" href="#">Collapse All</a>
  
</div>
<br class="funkybreak" />
<div class="roundcont">
  <div class="roundtop"><span class="rt"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
  <div class="roundcont_main" style="padding:14px 18px;">
  
		<table class="content_listing" cellpadding="6" cellspacing="0">
				<thead>
						<tr>
								<th>Preview</th>
								<th class="driver">Name</th>
								<th>Start Time</th>
								<th>End Time</th>
								<th>Submitted</th>
						</tr>
				</thead>
				<tbody>
		<?php
		if($this->contents){
				foreach($this->contents as $content) {
						$submitter = new User($content->user_id);
		?>
						<tr id="c<?= $content->id ?>" class="listitem">
								<td class="listh_icon"><?php
									if(preg_match('/image/',$content->mime_type)) {
										echo "<img class=\"icon_border\" src=\"".ADMIN_URL."/content/image/$content->id?width=50&amp;height=37\" alt=\"Icon\" />";
									} elseif(preg_match('/text/',$content->mime_type)) {
										echo "<img src=\"".ADMIN_BASE_URL."images/icon_text.gif\" alt=\"Icon\" />";
									} else {
										echo "&nbsp;";
									} ?></td>
								<td class="listtitle">
										<a href="<?= ADMIN_URL ?>/content/show/<?= $content->id ?>"><?= htmlspecialchars($content->name) ?></a>
								</td>
								<td><?=date("m/j/Y",strtotime($content->start_time))?></td>
								<td><?=date("m/j/Y",strtotime($content->end_time))?></td>
								<td><?php $user = new User($content->user_id); echo htmlspecialchars($user->name) ?></td>
						</tr>
		<?php
				}
		} else {
		?>
						<tr><td colspan="5">No Content Found</td></tr>
		<?php
		}
		?>
				</tbody>
		</table>
		
  </div>
  <div class="roundbottom"><span class="rb"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
</div>