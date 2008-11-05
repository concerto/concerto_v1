<script type="text/javascript"><!--
(function($) {
    $(document).ready(function() {
        var moderate = function(parent, action) {
            var content_id = $(parent).attr("id").replace(/c/,"");
            var loading = $("<div>").html("<h5>Loading.  Please wait...</h5>")
                .dialog({ autoResize: true,
                          draggable: false,
                          height: "auto",
                          modal: true,
                          overlay: { opacity: 0.5, background: "black" },
                          resizable: false,
                          title: "Loading..."
                        });
            $.ajax({type: "GET",
                    url: "<?=ADMIN_URL?>/moderate/confirm/" + action + "/ajax",
                    data: {"feed_id": <?=$this->feed->id?>,
                           "content_id": content_id},
                    success: function(html){
                        $(html)
                            .dialog({
                                autoResize: true,
                                buttons: {
                                    "Submit": function(){
                                        var posts = $(this).serializeArray();
                                        var actions = $(parent).prev().find("td.actions");
                                        var onError = function(){
                                            return;
                                            window.location = "<?=ADMIN_URL?>/moderate/confirm/" + action + "?feed_id=<?=$this->feed->id?>&content_id=" + content_id;
                                        };
                                        $.ajax({type: "POST",
                                                url: "<?=ADMIN_URL?>/moderate/post",
                                                data: posts,
                                                success: function(json){
                                                    if(json == true) {
                                                        if(action == "approve")
                                                            $(actions).html("Content Approved");
                                                        else
                                                            $(actions).html("Content Denied");
                                                    }
                                                    else onError();
                                                },
                                                error: onError,
                                                beforeSend: function(){
                                                    $(actions).html("Please Wait...");
                                                    $(parent).fadeOut("normal", function(){$(this).remove()});
                                                },
                                                dataType: "json"
                                        });
                                        $(this).dialog("destroy");
                                    },
                                    "Cancel": function(){ $(this).dialog("destroy"); }
                                },
                                draggable: false,
                                height: "auto",
                                modal: true,
                                overlay: { opacity: 0.5, background: "black" },
                                resizable: false,
                                title: "Moderate Content"
                            });
                        $(loading).dialog("destroy");
                    },
                    dataType: "html"
            });        
        }

        $(".approve").click(function(e) {
            e.preventDefault();
            var parent = $(this).parents("tr.details");
            moderate(parent, "approve");
            return false;
        });

        $(".deny").click(function(e) {
            e.preventDefault();
            var parent = $(this).parents("tr.details");
            moderate(parent, "deny");
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
                <a class="approve" title="Approve Content" href="<?=ADMIN_URL?>/moderate/confirm/approve?feed_id=<?=$this->feed->id?>&content_id=<?=$content->id?>"><span class="approve">Approve <img border="0" src="<?= ADMIN_BASE_URL ?>images/mod_check.gif" alt="" /></span></a>
                <a class="deny" title="Deny Content" href="<?=ADMIN_URL?>/moderate/confirm/deny?feed_id=<?=$this->feed->id?>&content_id=<?=$content->id?>"><span class="deny">Deny <img border="0" src="<?= ADMIN_BASE_URL ?>images/mod_ex.gif" alt="" /></span></a>
            </td>
            <td colspan="5">
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
    <span style="font-size:1.5em;font-weight:bold;color:#333;margin-bottom:12px;"><?= date('M j, Y h:i:s A',strtotime($content->start_time)) ?> - <?= date('M j, Y h:i:s A',strtotime($content->end_time)) ?></span> <? if($week_range > 1) echo "({$week_range} Weeks)" ?>
    <h2>Submitted by <strong><a href="<?= ADMIN_URL ?>/users/show/<?= $submitter->id ?>"><?= $submitter->name ?></a></strong></h2>
    <p>
    <?php $content->content = new Content($content->args[1]); ?>
    <?php
    	$feeds = $content->list_feeds();
    	$had_a_feed = 0;
      if(is_array($feeds)) {
         foreach ($feeds as $feed) {
						if($feed['moderation_flag']==1) {
							if($had_a_feed) {
                        echo ', ';
                     } else {
                        echo 'Already approved on feeds: <b>';
                        $had_a_feed = 1;
                     }
							echo $feed['feed']->name;
						}
				 }
				 echo '</b>';
			}
			if(!$had_a_feed) {
				echo "Not yet approved on any other feeds.";
			}
    ?>
    </b></p> 
</td>
<td>
</td>
</tr>
</table>
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
