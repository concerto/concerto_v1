<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technolgies Group)
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
 * @author       Web Technologies Group, $Author: mike $
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision: 551 $
 */
?><script type="text/javascript"><!--
(function($) {
    $(document).ready(function() {
      $('a.t-preview').lightBox({
        overlayBgColor: "#000",
        imageLoading: "<?=ADMIN_BASE_URL?>images/lightbox-ico-loading.gif",
        imageBtnClose: "<?=ADMIN_BASE_URL?>images/lightbox-btn-close.gif"
    });
 });
})(jQuery);
//--></script>
<?if($this->canEdit) {?>
<a href="<?=ADMIN_URL.'/screens/edit/'.$this->screen->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Edit Screen</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<a href="<?=ADMIN_URL.'/screens/subscriptions/'.$this->screen->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Manage Subscriptions</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<a href="<?=ADMIN_URL.'/screens/delete/'.$this->screen->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Delete Screen</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>
<?php
}

   if ($this->screen->width/$this->screen->height==(16/9)){
      $ratio = "16:9";
      if ($this->screen->is_connected()) {
      	if (!$this->screen->get_powerstate()) {
					$scrimg="screen_169_asleep_big.png";
				}
				else {
					$scrimg="screen_169_on_big.png";
				}
      } else {
      	$scrimg="screen_169_off_big.png";
      }
   } else if ($this->screen->width/$this->screen->height==(16/10)) {
      $ratio = "16:10";
      if ($this->screen->is_connected()) {
      	if (!$this->screen->get_powerstate()) {
					$scrimg="screen_169_asleep_big.png";
				}
				else {
					$scrimg="screen_169_on_big.png";
				}
      } else {
      	$scrimg="screen_169_off_big.png";
      }
   } else {
      $ratio = "4:3";
      if ($this->screen->is_connected()) {
      	if (!$this->screen->get_powerstate()) {
      		$scrimg="screen_43_asleep_big.png";
      	} 
      	else {
      		$scrimg="screen_43_on_big.png";
      	}
      } else {
      	$scrimg="screen_43_off_big.png";
      }
   }
?>

<div style="width:100%;">
	<div style="float:left; text-align:center; width:350px;"><br /><img src="<?echo ADMIN_BASE_URL?>/images/<?echo $scrimg?>" alt="" /></div>
   <div style="float:left">
	<?php if(!isAdmin()) { ?><br /><?php } ?>
	<h3>Location: <span class="emph"><? echo $this->screen->location?></span></h3>
	<h3>Size: <span class="emph"><?php echo $this->screen->width.' x '.$this->screen->height.' ('.$ratio; ?>)</span></h3>
	<h3>Status: 
		<span class="emph">
			<?php if($this->screen->is_connected()) { ?>
			 <? if($this->screen->get_powerstate()) { ?>
					<span style="color:green;">Online</span>
			 <? } else { ?>
					<span style="color:#aa0;">Asleep</span>
			 <? } ?>
			<?php } else { ?>
					<span style="color:red;">Offline</span>
			<?php } ?></span>(Last updated: <?php echo $this->screen->last_updated?>)
	</h3>
	<h3>Group: 
		<span class="emph">
			<? $group = new Group($this->screen->group_id) ?>
					<a href="<?= ADMIN_URL.'/groups/show/'.$group->id ?>"><?=$group->name?></a>
		</span>
	</h3>
	<h3>Template: 
		<span class="emph">
			<? $template = new Template($this->screen->template_id) ?>
					<a class="t-preview" href="<?= ADMIN_URL.'/templates/preview/'.$template->id.'?width=800' ?>"><?=$template->name?></a>
		</span>
	</h3>
<?php
if($this->canEdit && $this->screen->controls_display) {
?>
   <h3>Hours of Operation: <span class="emph"><?= $this->screen->time_on ?> - <?= $this->screen->time_off ?></span></h3>
<?php
}
?>

<?php
if(isAdmin()) { 
 $mac=str_pad($this->screen->mac_inhex,12,'0',STR_PAD_LEFT);
 $mac=join(str_split($mac,2),':');
?>
	<h3>MAC: <span class="emph"><?=$mac?></span></h3>
	<h3>Last IP: <span class="emph"><?=$this->screen->last_ip?></span></h3>
<?php } ?>
   </div>
</div>

<div style="clear:both;"></div><br />
  <h3>Subscriptions:</h3>

<?php
	$fields=$this->screen->list_fields();
	if(is_array($fields)) {
 	 foreach ($fields as $field) { 
?>

<div class="roundcont">
  <div class="roundtop"><span class="rt"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
  <div class="roundcont_main">
    <h1><span class="emph"><? echo $field->name ?></span> (Field)</h1>
    <ul>
	   <?php
		$positions = $field->list_positions();
		if($positions) {
		  foreach($positions as $position) {
			$feed = new Feed($position->feed_id);
	   ?>
		  <li><a href="<?=ADMIN_URL.'/browse/show/'.$feed->id?>"><?=$feed->name?></a></li>
	   <?php
		  }
	        } else echo "<li>(no subscriptions)</li>";
	   ?>
	 </ul>
  </div>
  <div class="roundbottom"><span class="rb"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
</div>

<?php   }
       }else echo "<p>No fields on this template</p>";
?> 
