<script type="text/javascript"><!--
$(function()
{
   $(document).ready(function() {
         //Show controls for interactive elements, which are hidden from scripting-disabled browsers
         //$(".newsfeed").append($("<a>").attr("id","news_expand").attr("class","seemore-div").attr("href","= ADMIN_URL ?>/users/newsfeed/= $_SESSION['user']->username ?>").html("<div class='roundbottom'><span class='rb'><span class='seemore-padding'>View More...</span></span></div>"));    this doesn't work in IE
         $("#plus_icon").show();
      });

   $("#news_expand").data('items', 7);
   
   $("#news_expand").click(function(event) {
		event.preventDefault();
         $.post("<?= ADMIN_URL ?>/users/notifications/<?= $_SESSION['user']->username ?>", {'start': $("#news_expand").data('items'), 'num': 7}, function(data) {
               $("<div>").css("overflow", "hidden").html(data).hide().appendTo($("#insertinto")).slideDown("slow");
               $("#news_expand").data('items',$("#news_expand").data('items')+7);
               if( data == "" )
                 $("#news_expand").before($("<span>").html("No more news")).remove();
            });
         return false;
      });
   
	$("#trigger").click(function(event) {
		event.preventDefault();
		$("#screenstat_hidden").slideToggle('normal',function(){
            if ($("#screenstat_hidden").css("display")=="none") {
               $("#plus_icon").show();
               $("#minus_icon").hide();
            } else {
               $("#plus_icon").hide();
               $("#minus_icon").show();
            }
      });
	});
}); 
//--></script>

<div class="roundcont newsfeed">
  <div class="roundtop"><span class="rt"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
  <div class="roundcont_main">
    <div style="text-align:right; float:right; width:85px;">
    	<a href="<?= ADMIN_URL ?>/users/newsfeed/<?= userName() ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">View All</div></div><div class="buttonright" style="width:10px;"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
    </div>
    <h1>News Feed</h1>
    <div id="insertinto">
    <?php 
    if(is_array($this->notifications)) {
       foreach($this->notifications as $newsfeed) {
    ?>
    	<p class="<?= $newsfeed->type ?>_<?= $newsfeed->msg ?>"><?= $newsfeed->text ?><span class="datesub"><?= date('M j', $newsfeed->timestamp) ?></span>
      </p><?php
       }
    }
    ?>
    </div>
  </div>
	<span style="display:none;" id="seemore"><span id="seemore-inner">View more...</span></span>
</div>

<div class="roundcont">
  <div class="roundtop"><span class="rt"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
  <div class="roundcont_main">
    <div style="float:left; width:75%;">
    	<h1>New Update Released on October 8</h1>
    	<p>Concerto has been updated to version <b><?= CONCERTO_VERSION ?></b>.  For more information, <a href="<?= ADMIN_BASE_URL ?>pages/show/docs/14">check out the release notes.</a></p>
    </div>
    <div style="float:right; text-align:right; width:23%;"><img src="<?= ADMIN_BASE_URL ?>/images/latest_version.gif" alt="" /></div>
    <div style="clear:both;"></div>
  </div>
  <div class="roundbottom"><span class="rb"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
</div>

<div class="roundcont">
  <div class="roundtop"><span class="rt"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
  <div class="roundcont_main">
    <h1>Operational Status</h1>
    <table style="text-align:center; font-size:1.3em; font-weight:bold;" cellpadding="6" cellspacing="0" width="100%">
    	<tr>
    		<td valign="middle" width="4%">
    			<div class="screenstat" style="width:100%;"><p><a href="#" id="trigger"><img id="plus_icon" style="display:none;" src="<?= ADMIN_BASE_URL ?>images/round_plus.gif" alt="" border="0" /><img id="minus_icon" style="display:none;" src="<?= ADMIN_BASE_URL ?>images/round_minus.gif" alt="" border="0" /></a></p></div>
    		</td>
    		<td valign="middle" width="21%">
    			<div class="screenstat" style="width:90%; margin-right:auto; margin-left:auto; border-right:solid 1px #666;"><p><a href="<?= ADMIN_URL ?>/screens/"><?php echo $this->screen_stats[3] ?> screens</a></p></div>
    		</td>
    		<td valign="middle" width="25%" style="color:green;">
    			<div class="screenstat"><p><?php echo $this->screen_stats[0] ?></p></div>
    			<div class="screenstat"><img src="<?= ADMIN_BASE_URL ?>images/screen_43_on_sm.gif" alt="" /></div>
    			<div class="screenstat"><p>online</p></div>
    		</td>
    		<td valign="middle" style="color:#aa0;" width="25%">
    			<div class="screenstat"><p><?php echo $this->screen_stats[2]; ?></p></div>
    			<div class="screenstat"><img src="<?= ADMIN_BASE_URL ?>images/screen_43_asleep_sm.gif" alt="" /></div>
    			<div class="screenstat"><p>asleep</p></div>
    		</td>
    		<td valign="middle" style="color:red;" width="25%">
    			<div class="screenstat"><p><?php echo $this->screen_stats[1]; ?></p></div>
    			<div class="screenstat"><img src="<?= ADMIN_BASE_URL ?>images/screen_43_off_sm.gif" alt="" /></div>
    			<div class="screenstat"><p>offline</p></div>
    		</td>
    	</tr>
    </table>

		<div id="screenstat_hidden">
			<br />
			<table class="edit_win" cellpadding="6" cellspacing="0">
	<?php
	
	foreach($this->screens as $screen){
   if ($screen->width/$screen->height==(16/9)){
      if ($screen->is_connected()) {
      	if (!$screen->get_powerstate()) {
					$status = "Asleep";
					$scrimg="screen_169_asleep.png";
				}
				else {
					$status = "Online";
					$scrimg="screen_169_on.png";
				}
      } else {
      	$status = "Offline";
      	$scrimg="screen_169_off.png";
      }
   } else if ($screen->width/$screen->height==(16/10)) {
      if ($screen->is_connected()) {
      	if (!$screen->get_powerstate()) {
					$status = "Asleep";
					$scrimg="screen_169_asleep.png";
				}
				else {
					$status = "Online";
					$scrimg="screen_169_on.png";
				}
      } else {
      	$status = "Offline";
      	$scrimg="screen_169_off.png";
      }
   } else {
      if ($screen->is_connected()) {
      	if (!$screen->get_powerstate()) {
					$status = "Asleep";
					$scrimg="screen_43_asleep.png";
      	} 
      	else {
					$status = "Online";
					$scrimg="screen_43_on.png";
      	}
      } else {
      	$status = "Offline";
      	$scrimg="screen_43_off.png";
      }
   }
	
	?>
				<tr valign="middle">
					<td class="icon" style="text-align:center; width:55px;"><div style="display:inline; margin-left:12px;width:50px; text-align:center">
						<a href="<?= url_for('screens','show',$screen->id)?>"><img height="35" class="icon" src="<?= ADMIN_BASE_URL ?>images/<?= $scrimg ?>" alt="<?= $status ?>" title="<?= $status ?>" /></a>
					</div></td>
					<td><span class="emph"><a href="<?= url_for('screens','show',$screen->id)?>"><?=$screen->name?></a></span>, a <?=$screen->width.'x'.$screen->height?> display in <b><?=$screen->location?></b></td>
				</tr>
	<?php
	}
	?>
			</table>
    
    </div>

  </div>
  <div class="roundbottom"><span class="rb"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
</div>
