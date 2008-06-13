<h2>Click on a type of content to show from a particular feed.</h2>
<table class="edit_win" cellpadding="6" cellspacing="0">
<?php
foreach($this->feeds as $feed) {
    if(!$feed->user_priv($_SESSION["user"])) continue;
    $types = $feed->get_types();
    if($types == false) continue;
?>
<tr>
    <td>
      <h1><?= htmlspecialchars($feed->name) ?> Feed</h1>
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
      <h4>Moderated by <? $group = new Group($feed->group_id) ?><a href="<?= ADMIN_URL ?>/groups/show/<?= $group->id ?>"><?= $group->name ?></a></h4>
    </td>
</tr>
<? } ?>
</table>
<div style="clear:left"></div><br/><br/>
<h2>Would you like to see a feed for your campus organization? <a href="<?=ADMIN_URL.'/feeds/request' ?>">Request a feed</a> today!</h2>
