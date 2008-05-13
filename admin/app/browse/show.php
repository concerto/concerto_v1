<script type="text/javascript"><!--
(function($) {
    $.fn.extend({
        expand_details: function(){
            if($(this).data("loaded") == undefined) {
                $(this).data("loaded", 0);
                var parent = this;
                $.ajax({type: "POST",
                        url: "<?=ADMIN_URL?>/browse/details",
                        data: {"id": $(parent).attr("id").replace(/c/,"")},
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
<h3>Group: <span class="emph"><a href="<?=ADMIN_URL.'/groups/show/'.$this->group->id?>"><?= $this->group->name ?></a></span></h3>
<?php
if($this->feed->user_priv($_SESSION['user'], "moderate")){
?>
<h3>Moderation status: <span class="emph"><a href="<?=ADMIN_URL?>/moderate/feed/<?=$this->feed->id?>"><?= $this->waiting > 0 ? $this->waiting : "No" ?> items awaiting moderation</a></span></h3>
<?
}
?>
<br />
<div style="float:left; width:50%;">
  <h3>
<?php
if(!isset($this->args[4]))
    echo "Active Content";
else
    echo "<a href=\"".ADMIN_URL."/browse/show/{$this->feed->id}/type/{$this->type_id}/\">Active Content</a>";
echo " | ";
if($this->args[4] == "expired")
    echo "Expired Content";
else
    echo "<a href=\"".ADMIN_URL."/browse/show/{$this->feed->id}/type/{$this->type_id}/expired/\">Expired Content</a>";
if($this->feed->user_priv($_SESSION['user'], "moderate")) {
    echo " | ";
    if($this->args[4] == "declined")
        echo "Declined Content";
    else
        echo "<a href=\"".ADMIN_URL."/browse/show/{$this->feed->id}/type/{$this->type_id}/declined/\">Declined Content</a>";
}
?></h3>
</div>
<div style="float:right:width:50%;text-align:right;">
  <h3>
    <a id="expandall" href="#">Expand All</a> | <a id="collapseall" href="#">Collapse All</a>
  </h3>
</div>
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
                <a href="<?= ADMIN_URL ?>/content/show/<?= $content->id ?>"><?= $content->name ?></a>
            </td>
            <td><?=date("m/j/Y",strtotime($content->start_time))?></td>
            <td><?=date("m/j/Y",strtotime($content->end_time))?></td>
            <td><?php $user = new User($content->user_id); echo $user->name ?></td>    
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
