<?php if ($this->canEdit) {?>
<a href="<?=ADMIN_URL.'/groups/add/'.$this->group->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Add a User</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<a href="<?=ADMIN_URL.'/groups/remove/'.$this->group->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Remove a User</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<a href="<?=ADMIN_URL.'/groups/delete/'.$this->group->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Delete Group</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>
<?php } ?>
</p>

      <h3>Members:</h3>
      <ul>
	<? if(is_array($this->group->get_members())) 
	foreach ($this->group->get_members() as $user) { ?>
	   <li><a href="<?echo ADMIN_URL ?>/users/show/<?echo $user->username ?>"><?echo $user->name ?></a>
	   <?if ($this->canEdit) { ?>
           ( <a href="<?echo ADMIN_URL ?>/groups/remove/<?echo $this->group->id ?>?user=<?echo $user->username?>">Remove</a> )	     
	   <? } ?>
	 </li>
	<? }  else echo "<p>None</p>";?> 
      </ul>

	<?if(is_array($this->feeds)&&count($this->feeds)>0) { ?>
	<br />
	<h3>Feeds:</h3>
	
	<table class="edit_win" cellpadding="6" cellspacing="0">
	<?php
	foreach($this->feeds as $feed) {
			if(!$feed->user_priv($_SESSION["user"])) continue;
			$types = $feed->get_types();
			if($types == false) $types = array();
	?>
	<tr>
			<td>
				<h1><a style="color:#000 !important;" href="<?= ADMIN_URL?>/browse/feed/<?= $feed->id ?>"><?= htmlspecialchars($feed->name) ?> Feed</a></h1>
				<?php
				$list = array();
				foreach($types as $type_id => $type_name) {
						$sql_act = "SELECT COUNT(id) FROM content LEFT JOIN feed_content on content.id = content_id WHERE feed_id = {$feed->id} AND type_id=$type_id AND content.end_time > NOW() AND feed_content.moderation_flag = 1;";
						$num_act = sql_query1($sql_act);
						$sql_exp = "SELECT COUNT(id) FROM content LEFT JOIN feed_content on content.id = content_id WHERE feed_id = {$feed->id} AND type_id=$type_id AND content.end_time < NOW() AND feed_content.moderation_flag = 1;";
						$num_exp = sql_query1($sql_exp);
						$list[] = "<a href=\"".ADMIN_URL."/browse/show/{$feed->id}/type/$type_id\" title=\"$num_act active and $num_exp expired $type_name items in the {$feed->name} feed\"><span class=\"buttonsel\"><div class=\"buttonleft\"><img src=\"".ADMIN_BASE_URL."/images/buttonsel_left.gif\" style=\"border:0px !important;\" border=\"0\" alt=\"\" /></div><div class=\"buttonmid\"><div class=\"buttonmid_padding\">$type_name ($num_act)</div></div><div class=\"buttonright\"><img src=\"".ADMIN_BASE_URL."/images/buttonsel_right.gif\" style=\"border:0px !important;\" border=\"0\" alt=\"\" /></div></span></a>";
				}
				if(sizeof($list)>0){
						echo join($list);
				}
				?>
			</td>
			<td>
         <h4>Expired Content: <?=$feed->content_count('1','expired');?></h4>
			</td>
	</tr>
	<? } ?>
	</table>

	

	<? } ?>

	<? if(is_array($this->screens)&&count($this->screens)>0) { ?>
	<br />
	<h3>Screens:</h3>
	<?php
foreach($this->screens as $screen){
   if ($screen->width/$screen->height==(16/9)){
      if ($screen->is_connected()) {
      	if (!$screen->get_powerstate()) {
					$status = "Asleep";
					$statcolor = "#aa0";
					$scrimg="screen_169_asleep.png";
				}
				else {
					$status = "Online";
					$statcolor = "green";
					$scrimg="screen_169_on.png";
				}
      } else {
      	$statcolor = "red";
      	$status = "Offline";
      	$scrimg="screen_169_off.png";
      }
   } else if ($screen->width/$screen->height==(16/10)) {
      if ($screen->is_connected()) {
      	if (!$screen->get_powerstate()) {
					$status = "Asleep";
					$statcolor = "#aa0";
					$scrimg="screen_169_asleep.png";
				}
				else {
					$status = "Online";
					$statcolor = "green";
					$scrimg="screen_169_on.png";
				}
      } else {
      	$statcolor = "red";
      	$status = "Offline";
      	$scrimg="screen_169_off.png";
      }
   } else {
      if ($screen->is_connected()) {
      	if (!$screen->get_powerstate()) {
					$status = "Asleep";
					$statcolor = "#aa0";
					$scrimg="screen_43_asleep.png";
      	} 
      	else {
					$status = "Online";
					$statcolor = "green";
					$scrimg="screen_43_on.png";
      	}
      } else {
      	$statcolor = "red";
      	$status = "Offline";
      	$scrimg="screen_43_off.png";
      }
   }
	?>
  <a href="<?echo ADMIN_URL?>/screens/show/<? echo $screen->id ?>">
    <div class="roundcont roundcont_sf">
			<div class="roundtop"><span class="rt"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
			<div class="roundcont_main sf">
				<img src="<?echo ADMIN_BASE_URL?>/images/<?echo $scrimg?>" height="100" alt="" /><br />
				<div class="sf_header">
					<p style="color:<? echo $statcolor ?>;"><? echo $status ?></p>
					<h1><? echo $screen->name?></h1>
					<h2><? echo $screen->location?></h2>
				</div>
				<div style="clear:both;"></div>
			</div>
			<div class="roundbottom"><span class="rb"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
		</div>
  </a>

	<?php
	}
	?>
	<? } ?>




