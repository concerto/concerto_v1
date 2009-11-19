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
?><script type="text/javascript"><!--
$(function()
{
   $("ul#maintab").tabs();
   //Show controls for interactive elements, which are hidden from scripting-disabled browsers
   $("#plus_icon").show();
   $("#seemore").show();

   $("#news_expand").data('items', 7);
   
   $("#seemore").click(function(event) {
		event.preventDefault();
	if($(this).data("news"))
		return;
      $.post("<?= ADMIN_URL ?>/users/notifications/<?= $this->user->username ?>", {'start': $("#news_expand").data('items'), 'num': 7}, function(data) {
         $("<div>").css("overflow", "hidden").html(data).hide().appendTo($("#news_expand")).slideDown("slow");
               $("#news_expand").data('items',$("#news_expand").data('items')+7);
               if( data == "" ) {
                 $("#news_expand").after($("<span>").html("No more news"));
		 $("#seemore").data("news", 1);
		}
            });
         return false;
      });
}); 
//--></script>

<?php if ($this->canEdit) {?>
<a href="<?=ADMIN_URL.'/users/edit/'.$this->user->username ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Edit Profile</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>
</a>
<?php } ?>
</p>
<h3>Username: <span class="emph"><? echo $this->user->username?></span></h3>
<h3>Groups:</h3>
<ul>
<?php
  if(count($this->groups)<1)
     echo '<li><em>none</em></li>';
  foreach($this->groups as $group)
     echo '<li>'.$group.'</li>';
?>
</ul>
<?php if($this->canEdit) {?>
<h3>Contact: <span class="emph"><a href="mailto:<?php echo $this->user->email?>"><?php echo $this->user->email?></a></h3>

<br />
<div class="roundcont newsfeed">
  <div class="roundtop"><span class="rt"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
  <div class="roundcont_main">
    <div style="text-align:right; float:right; width:85px;">
    	<a href="<?= ADMIN_URL ?>/users/newsfeed/<?=$this->user->username ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">View All</div></div><div class="buttonright" style="width:10px;"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
    </div>
    <h1>News Feed</h1>
    <div id="news_expand">
    <?php 
    if(is_array($this->notifications)) {
       foreach($this->notifications as $newsfeed) {
    ?>
    	<p class="<?= $newsfeed->type ?>_<?= $newsfeed->msg ?>"><?= $newsfeed->text ?><span class="datesub"><?= date('M j', $newsfeed->timestamp) ?></span>
        <?php
          if($newsfeed->has_extra){
            echo '<br/><span class="newsfeed_reason">'.$newsfeed->additional.'</span>';
          }
        ?>
      </p><?php
       }
    }
    ?>
    </div>
  </div>
  <span style="display:none;" id="seemore"><span id="seemore-inner">View more...</span></span>
  <noscript><div class="roundbottom"><span class="rb"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div></noscript>
</div>

<? } ?>
<br />
<ul id="maintab">
	<li class="first"><a class="approved" href="#approved"><h1>Approved</h1></a></li>
	<li class="middle"><a class="denied" href="#denied"><h1>Denied</h1></a></li>
  <li class="last"><a class="pending" href="#pending"><h1>Pending</h1></a></li>
</ul>
<br class="funkybreak" />
<div id="submissions" class="roundcont">
  <div class="roundtop"><span class="rt"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
  <div class="roundcont_main">

<div id="approved" class="contentstyle">
<h1>Approved Submissions</h1>

<?php
unset($urls);
if(array_key_exists('contents', $this) && array_key_exists('approved', $this->contents) && is_array($this->contents['approved']) && count($this->contents['approved']>=1))
{
foreach(array_keys($this->contents['approved']) as $field)
  $urls[]='<a href="#approved_'.$field.'">'.$field.'</a>'; ?>
	<p>Jump to: <?=join(" | ", $urls)?></p>
	<?php
} else {
	echo "<p><em>This user has had no content approved on one or more feeds.</em></p>";
}
if(array_key_exists('contents', $this) && array_key_exists('approved', $this->contents) && is_array($this->contents['approved']))
foreach($this->contents['approved'] as $field=>$contents)
{
	echo "<br /><br />";
?>
	<?php echo "<a name=\"approved_$field\"></a><h2>$field<span class=\"toplink\"><a href=\"#submissions\">top</a></span></h2>"; ?>
	<table class="content_listing" cellpadding="6" cellspacing="0">
			<thead>
					<tr>
							<th>Preview</th>
							<th class="driver">Name</th>
							<th>Start Time</th>
							<th>End Time</th>
					</tr>
			</thead>
			<tbody>
	<?php
	if($contents){
			foreach($contents as $content) {
					$submitter = new User($content->user_id); ?>
					<tr id="c<?= $content->id ?>" class="listitem listitem_none">
							<td class="listh_icon"><?php
								if(preg_match('/image/',$content->mime_type)) {
									echo "<a href=\"" .ADMIN_URL. "/content/show/$content->id\"><img class=\"icon_border\" src=\"".ADMIN_URL."/content/image/$content->id?width=50&amp;height=37\" alt=\"Icon\" /></a>";
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
					</tr>
	<?php
			}
	} else {
	?>
					<tr><td colspan="4">This user has had no content approved on one or more feeds.</td></tr>
	<?php
	}
	?>
			</tbody>
	</table>

<?php
}
?>

</div>
<div id="denied" class="contentstyle">

<h1>Denied Submissions</h1>

<?php
unset($urls);
if(is_array($this->contents['denied']) && count($this->contents['denied']>=1))
{
foreach(array_keys($this->contents['denied']) as $field)
  $urls[]='<a href="#denied_'.$field.'">'.$field.'</a>'; ?>
	<p>Jump to: <?=join(" | ", $urls)?></p>
	<?php
} else {
	echo "<p><em>This user has had no content denied on all feeds.</em></p>";
}
if(is_array($this->contents['denied']))
foreach($this->contents['denied'] as $field=>$contents)
{
	echo "<br /><br />";
?>
	<?php echo "<a name=\"denied_$field\"></a><h2>$field<span class=\"toplink\"><a href=\"#submissions\">top</a></span></h2>"; ?>
	<table class="content_listing" cellpadding="6" cellspacing="0">
			<thead>
					<tr>
							<th>Preview</th>
							<th class="driver">Name</th>
							<th>Start Time</th>
							<th>End Time</th>
					</tr>
			</thead>
			<tbody>
	<?php
	if($contents){
			foreach($contents as $content) {
					$submitter = new User($content->user_id); ?>
					<tr id="c<?= $content->id ?>" class="listitem listitem_none">
							<td class="listh_icon"><?php
								if(preg_match('/image/',$content->mime_type)) {
									echo "<a href=\"" .ADMIN_URL. "/content/show/$content->id\"><img class=\"icon_border\" src=\"".ADMIN_URL."/content/image/$content->id?width=50&amp;height=37\" alt=\"Icon\" /></a>";
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
					</tr>
	<?php
			}
	} else {
	?>
					<tr><td colspan="4">This user has had no content denied on all feeds.</td></tr>
	<?php
	}
	?>
			</tbody>
	</table>

<?php
}
?>

</div>
<div id="pending" class="contentstyle">

<h1>Pending Submissions</h1>

<?php
unset($urls);
if(is_array($this->contents['pending']) && count($this->contents['pending']>=1))
{
foreach(array_keys($this->contents['pending']) as $field)
  $urls[]='<a href="#pending_'.$field.'">'.$field.'</a>'; ?>
	<p>Jump to: <?=join(" | ", $urls)?></p>
	<?php
} else {
	echo "<p><em>This user has had no content pending moderation on all feeds.</em></p>";
}
if(is_array($this->contents['pending']))
foreach($this->contents['pending'] as $field=>$contents)
{
	echo "<br /><br />";
?>
	<?php echo "<a name=\"pending_$field\"></a><h2>$field<span class=\"toplink\"><a href=\"#submissions\">top</a></span></h2>"; ?>
	<table class="content_listing" cellpadding="6" cellspacing="0">
			<thead>
					<tr>
							<th>Preview</th>
							<th class="driver">Name</th>
							<th>Start Time</th>
							<th>End Time</th>
					</tr>
			</thead>
			<tbody>
	<?php
	if($contents){
			foreach($contents as $content) {
					$submitter = new User($content->user_id); ?>
					<tr id="c<?= $content->id ?>" class="listitem listitem_none">
							<td class="listh_icon"><?php
								if(preg_match('/image/',$content->mime_type)) {
									echo "<a href=\"" .ADMIN_URL. "/content/show/$content->id\"><img class=\"icon_border\" src=\"".ADMIN_URL."/content/image/$content->id?width=50&amp;height=37\" alt=\"Icon\" /></a>";
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
					</tr>
	<?php
			}
	} else {
	?>
					<tr><td colspan="4">This user has had no content pending moderation on all feeds.</td></tr>
	<?php
	}
	?>
			</tbody>
	</table>

<?php
}
?>
</div>
  </div>

  <div class="roundbottom"><span class="rb"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
</div>
