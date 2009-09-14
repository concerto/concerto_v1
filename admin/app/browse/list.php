<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technologies Group)
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
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */
?>

<div class="roundcont">
  <div class="roundtop"><span class="rt"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
  <div class="roundcont_main">
    <div style="float:left; width:65%;">
    	<a href="<?= ADMIN_URL ?>/wall"><img border="0" src="<?= ADMIN_BASE_URL ?>/images/wall/wall-words.gif" alt="" /></a>
    	<br /><br />
    	<p><a href="<?= ADMIN_URL ?>/wall">Concerto Wall</a> is an interactive feature that allows you to view live graphical Concerto content.  Visit the Wall now to peruse live content in a completely new way!</p>
    </div>
    <div style="float:right; text-align:right; width:33%;"><a href="<?= ADMIN_URL ?>/wall"><img src="<?= ADMIN_BASE_URL ?>/images/wall/wall-announce.jpg" border="0" alt="" /></a></div>
    <div style="clear:both;"></div>
  </div>
  <div class="roundbottom"><span class="rb"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
</div>

<?php if(isAdmin()) { ?>
<a href="<?=ADMIN_URL.'/feeds/new' ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">New Feed</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<? } else { ?>
<a href="<?=ADMIN_URL.'/feeds/request' ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Request a Feed</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<? } ?>
<div style="clear:both;height:6px;"></div>

<?php
$public_feed['key'] = 'public_feeds';
$public_feed['name'] = 'Normal Feeds';
$public_feed['desc'] = 'These feeds are open to everyone submitting messages, and may appear on one or more Concerto screens.';

$restrict_feed['key'] = 'restricted_feeds';
$restrict_feed['name'] = 'Restricted Feeds';
$restrict_feed['desc'] = 'These feeds are not open for the public to submit content, but they can be shown on any screen.';

$private_feed['key'] = 'private_feeds';
$private_feed['name'] = 'Private Feeds';
$private_feed['desc'] = 'These feeds are only available to people within your user group.  Others outside of your group cannot see these feeds or subscribe their screens to them.';

$feed_keys = array($public_feed, $restrict_feed, $private_feed);

foreach($feed_keys as $feed_key){
    if(count($this->feeds[$feed_key['key']]) > 0){
?>
<h4 class="browseh"><?=$feed_key['name']?></h4>
<p class="browsep"><?=$feed_key['desc']?></p>
<table class="edit_win" cellpadding="0" cellspacing="0">
<?php

foreach($this->feeds[$feed_key['key']] as $feed) {
    if(!$feed->user_priv($_SESSION["user"])) continue;
    $types = $feed->get_types();
    if($types == false) $types = array();
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
<br />
<?
    }
}
?>
<div style="clear:left"></div><br/><br/>
<h2>Would you like to see a feed for another category of content? <a href="<?=ADMIN_URL.'/feeds/request' ?>">Request a feed</a> today!</h2>
