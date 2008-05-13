<script type="text/javascript"><!--
(function($) {
    $(document).ready(function() {
        $(".approve").click(function() {
            var parent = $(this).parents("tr.details");
            var content_id = $(parent).attr("id").replace(/c/,"");
            var duration = parseInt($(parent).find("input[name=duration]").attr("value")) * 1000;
            $.ajax({type: "POST",
                    url: "<?=ADMIN_URL?>/moderate/post",
                    data: {"feed_id": <?=$this->feed->id?>,
                           "content_id": content_id,
                           "action": "approve",
                           "duration": duration},
                    success: function(json){
                        if(json == true) {
                            $(parent).prev().find("td.actions").html("Content Approved");
                            $(parent).fadeOut("slow");
                        } else {
                            window.location = "<?=ADMIN_URL?>/moderate/approve/<?=$this->feed->id?>/" + content_id;
                        }
                    },
                    error: function(){
                        window.location = "<?=ADMIN_URL?>/moderate/approve/<?=$this->feed->id?>/" + content_id;
                    },
                    dataType: "json"
            });
            return false;
        });

        $(".deny").click(function() {
            var parent = $(this).parents("tr.details");
            var content_id = $(parent).attr("id").replace(/c/,"");
            $.ajax({type: "POST",
                    url: "<?=ADMIN_URL?>/moderate/post",
                    data: {"feed_id": <?=$this->feed->id?>,
                           "content_id": content_id,
                           "action": "deny"},
                    success: function(json){
                        if(json == true) {
                            $(parent).prev().find("td.actions").html("Content Denied");
                            $(parent).fadeOut("slow");
                        } else {
                            window.location = "<?=ADMIN_URL?>/moderate/deny/<?=$this->feed->id?>/" + content_id;
                        }
                    },
                    error: function(){
                        window.location = "<?=ADMIN_URL?>/moderate/deny/<?=$this->feed->id?>/" + content_id;
                    },
                    dataType: "json"
            });
            return false;
        });

        $(".i-preview").each(function(){
            $(this).lightBox({
                overlayBgColor: "#000",
                imageLoading: "<?=ADMIN_BASE_URL?>images/lightbox-ico-loading.gif",
                imageBtnClose: "<?=ADMIN_BASE_URL?>images/lightbox-btn-close.gif"
            });
        });
    });
})(jQuery);
//--></script>
<h2>Use the buttons to approve or deny each piece of content as you see appropriate for this feed.</h2>
<table class="content_listing moderate" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th style="text-align:right !important;">Actions</th>
            <th>Preview</th>
            <th class="driver_moderate">Name</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Submitted</th>
        </tr>
    </thead>
    <tbody>
<?php
if(isset($this->contents)) {
   foreach($this->contents as $content) {
      $submitter = new User($content->user_id);
      $week_range = date('W',strtotime($content->end_time)) - date('W',strtotime($content->start_time));
?>
        <tr class="listitem sel_listitem">
            <td class="actions" style="padding:12px !important"></td> 
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
            <td><?=date("m/j/y",strtotime($content->start_time))?></td>
            <td><?=date("m/j/y",strtotime($content->end_time))?></td>
            <td><?php $user = new User($content->user_id); echo $user->name ?></td>    
        </tr>
        <tr id="c<?=$content->id?>" class="details">
            <td class="actions">
                <a class="approve" href="<?=ADMIN_URL?>/moderate/approve/<?=$this->feed->id?>/<?=$content->id?>"><span class="approve">Approve <img border="0" src="<?= ADMIN_BASE_URL ?>images/mod_check.gif" alt="" /></span></a>
                <a class="deny" href="<?=ADMIN_URL?>/moderate/deny/<?=$this->feed->id?>/<?=$content->id?>"><span class="deny">Deny <img border="0" src="<?= ADMIN_BASE_URL ?>images/mod_ex.gif" alt="" /></span></a>
            </td>
            <td colspan="5">
<form>
<table>
<tr>
<td class="preview <? if(preg_match('/text/',$content->mime_type)) { echo " text_bg"; } ?>" style="width:250px">
<? if(preg_match('/image/',$content->mime_type)) { ?>
    <a class="i-preview" href="<?= ADMIN_URL ?>/content/image/<?= $content->id ?>"><img src="<?= ADMIN_URL ?>/content/image/<?= $content->id ?>?width=250&amp;height=200" alt="" /></a>
<? } elseif(preg_match('/text/',$content->mime_type)) { ?>
    <span class="emph"><?= $content->content ?></span>
<? } ?>
</td>
<td>
    <h1><a href="<?= ADMIN_URL ?>/content/show/<?= $content->id ?>"><?= $content->name ?></a></h1>
    <span style="font-size:1.5em;font-weight:bold;color:#333;margin-bottom:12px;"><?= date('M j, Y',strtotime($content->start_time)) ?> - <?= date('M j, Y',strtotime($content->end_time)) ?></span> <? if($week_range > 1) echo "({$week_range} Weeks)" ?>
    <h2>Display duration: <img border="0" src="<?= ADMIN_BASE_URL ?>images/mod_dur.gif" alt="" /><input type="text" name="duration" value="<?=$content->duration/1000?>" size="2" /> seconds</h2>
    <h2>Submitted by <strong><a href="<?= ADMIN_URL ?>/users/show/<?= $submitter->id ?>"><?= $submitter->name ?></a></strong></h2>
</td>
<td>
</td>
</tr>
</table>
</form>
            </td>
        </tr>
<?
   }
} else {
?>
        <tr><td colspan="6">No content awaiting moderation found</td></tr>
<?
}
?>
    </tbody>
</table>
