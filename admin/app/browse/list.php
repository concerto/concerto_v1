<?php if(isAdmin()) { ?>
<a href="<?=ADMIN_URL.'/feeds/new' ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">New Feed</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<? } else { ?>
<a href="<?=ADMIN_URL.'/feeds/request' ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Request a Feed</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<? } ?>
<div style="clear:both;height:12px;"></div>
<h2>Click on a type of content to show from a particular feed, or on the title of the feed you wish to view. <a href="http://signage.rpi.edu/admin/pages/show/docs/23"><img class="icon" border="0" src="<?= ADMIN_BASE_URL ?>images/help_button.gif" alt="Extra Help" title="Extra Help" /></a></h2>
<table class="edit_win" cellpadding="0" cellspacing="0">
<?php
foreach($this->feeds as $feed) {
    if(!$feed->user_priv($_SESSION["user"])) continue;
    $types = $feed->get_types();
    if($types == false) continue;
?>
	<tr>
    <td style="padding-bottom:0px !important;"><h1><a style="color:#000 !important;" href="<?= ADMIN_URL?>/browse/feed/<?= $feed->id ?>"><?= htmlspecialchars($feed->name) ?> Feed</a></h1></td>
    <td style="padding-bottom:0px !important;"><h4>Moderated by <? $group = new Group($feed->group_id) ?><a href="<?= ADMIN_URL ?>/groups/show/<?= $group->id ?>"><?= $group->name ?></a></h4></td>
	</tr>
	<tr>
		<td class="merged" colspan="2"><p><b><?= $feed->description ?></b></p></td>
	</tr>
	<tr>
		<td class="merged" colspan="2">
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
	</tr>
<? } ?>
</table>
<div style="clear:left"></div><br/><br/>
<h2>Would you like to see a feed for your campus organization? <a href="<?=ADMIN_URL.'/feeds/request' ?>">Request a feed</a> today!</h2>
