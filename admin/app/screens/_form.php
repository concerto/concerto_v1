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
(function($) {
    $(document).ready(function() {
      $('a.t-preview').lightBox({
        overlayBgColor: "#000",
        imageLoading: "<?=ADMIN_BASE_URL?>images/lightbox-ico-loading.gif",
        imageBtnClose: "<?=ADMIN_BASE_URL?>images/lightbox-btn-close.gif",
        imageBtnPrev: "<?=ADMIN_BASE_URL?>images/lightbox-btn-prev.gif",
        imageBtnNext: "<?=ADMIN_BASE_URL?>images/lightbox-btn-next.gif",
        txtImage: 'Template'
    });
 });
})(jQuery);
//--></script>

<!-- Beginning Screen Form -->
<?php
  //assuming $this->screen is null or the screen we want to edit
  $screen = $this->screen;

	if(isset($screen->width) && isset($screen->height)){
		if ($screen->width/$screen->height==(16/9)){
			$ratio = "16:9";
			if ($screen->is_connected()) {
				if (!$screen->get_powerstate()) {
					$scrimg="screen_169_asleep_big.png";
				}
				else {
					$scrimg="screen_169_on_big.png";
				}
			} else {
				$scrimg="screen_169_off_big.png";
			}
		} else if ($screen->width/$screen->height==(16/10)) {
			$ratio = "16:10";
			if ($screen->is_connected()) {
				if (!$screen->get_powerstate()) {
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
			if ($screen->is_connected()) {
				if (!$screen->get_powerstate()) {
					$scrimg="screen_43_asleep_big.png";
				} 
				else {
					$scrimg="screen_43_on_big.png";
				}
			} else {
				$scrimg="screen_43_off_big.png";
			}
		}
	}
?>

<!-- Begin Screen Form General Section -->
<h3>General Screen Settings <a href="<?= ADMIN_URL ?>/pages/show/docs/19#s2"><img class="icon" border="0" src="<?= ADMIN_BASE_URL ?>images/help_button.gif" alt="Extra Help" title="Extra Help" /></a></h3>
<br />
<div style="text-align:center; width:28%; float:left;">
	<img src="<?=ADMIN_BASE_URL?>/images/<?echo $scrimg?>" height="150" style="margin-right:15px !important;" alt="" />
</div>
<div style="clear:none; width:70%; float:right;">
	<table style="clear:none;" class='edit_win' cellpadding='6' cellspacing='0'>
		<tr>
			<td class='firstrow'><h5>Screen Name</h5></td>
			<td class='edit_col firstrow'>
				<input type="text" id="name" name="screen[name]" value="<?=$screen->name?>">
			</td>
		</tr>
		<tr>
			<td><h5>Screen Location</h5></td>
			<td>
				<input type="text" id="desc" name="screen[location]" value="<?=$screen->location?>">
			</td>
		</tr>
		<tr>
			<td><h5>Screen Latitude, Longitude</h5></td>
			<td>
				<input type="text" id="latitude" name="screen[latitude]" class="small" value="<?=$screen->latitude?>">&nbsp;&nbsp;,&nbsp;&nbsp;
				<input type="text" id="longitude" name="screen[longitude]" class="small" value="<?=$screen->longitude?>">
			</td>
		</tr>
		<tr>
			<td><h5>Screen Size<br />(W x H, in pixels)</h5></td>
			<td>
				<input type="text" id="width" name="screen[width]" class="small" value="<?=$screen->width?>">&nbsp;&nbsp;x&nbsp;&nbsp;<input type="text" id="height" name="screen[height]" class="small" value="<?=$screen->height?>">
			</td>
		</tr>
		<tr>
			<td><h5>MAC Address</h5></td>
			<td>
				<input type="text" id="mac_inhex" name="screen[mac_inhex]" value="<?=$screen->mac_inhex?>">
			</td>
		</tr>

		<tr>
			<td><h5>Owning Group</h5></td>
			<td><select name="screen[group]">
			<?php $groups = sql_select('group',array('id','name'));
							 if(is_array($groups))
								 foreach($groups as $group) {
				 ?>
						<option value="<?= $group[id] ?>"<?php if($screen->group_id==$group[id]) echo ' SELECTED'; ?>><?=$group[name]?></option>
				 <?php   } ?>
				 </select></td>
		</tr>
<? if (isAdmin() && isset($screen->id))  { ?>
	 	<tr>
		 	<td><h5>Controls display</h5><p>Whether or not the machine controls the power state of the display.</p></td>
		 	<td>
		 		<select name="screen[controls_display]">
			 		<option value="0"<?=$screen->controls_display?"":" selected"?>>No</option>
			 		<option value="1"<?=$screen->controls_display?" selected":""?>>Yes</option>
			 	</select>
		 	</td>
	 	</tr>
<? } ?>
<? if (isset($screen->id) && (isAdmin() || $screen->controls_display)) { ?>
	 	<tr>
		 	<td><h5>Display On/Off Times</h5><p>What time should the system turn the screen on and off? <strong>Please specify hh:mm in 24-hour time</strong>, e.g. 18:00 for 6:00 pm, 00:00 for the very beginning of the day, or 23:59 for the end of the day.</p></td>
		 	<td>
		 		<input type="text" name="screen[time_on]" class="small" value="<?=$screen->time_on?>" />&nbsp;&nbsp;to&nbsp;&nbsp;<input type="text" name="screen[time_off]" class="small" value="<?=$screen->time_off?>" />
		 	</td>
	 	</tr>
<? } ?>
	</table>
	<br />
	<table style="clear:none;" class='edit_win' cellpadding='6' cellspacing='0'>
		<tr>
			<td>
				<h5>Screen Template</h5>
				<p>Click on a thumbnail for a larger view.</p>
				<br />
				<?php
							if(is_array($this->avail_templates))
								echo "<h4 style='color:#333;margin-bottom:12px;padding-bottom:6px;border-bottom:solid 1px #ccc;'>Normal Templates</h4>";
								foreach($this->avail_templates as $template) {
				?>
				<div style="margin:5px;height:200px;float:left;text-align:center;width:200px;">
					<input class="template" type="radio" name="screen[template]" style="vertical-align:middle"  value="<?= $template->id ?>"<?php if($screen->template_id==$template->id) echo ' checked'; ?>>
						<a href="<?=ADMIN_URL.'/templates/preview/'.$template->id.'?width=800'?>" class="t-preview"><img style="vertical-align:middle; margin:10px;" src="<?=ADMIN_URL.'/templates/preview/'.$template->id.'?width=150'?>" width="150" height="<?= round(150*$screen->height/$screen->width) ?>" alt="<?= $template->name ?>"/></a>
						<p style="color:#333;"><b><?= $template->name ?></b><br />
						<? if(strlen($template->creator) > 0){ echo "Created by: $template->creator <br />"; } ?>
						<? if(strtotime($template->modified) > 0){echo "Last Updated: " . date("M j, Y", strtotime($template->modified));} ?>
						</p>
					</input>
				</div>
	<?php   } ?>
				
				<?php
							if(isAdmin() && is_array($this->admin_templates)){
							echo "<div style='clear:both;'></div><h4 style='color:#333;padding-bottom:6px;margin-bottom:12px;border-bottom:solid 1px #ccc;'>Hidden Templates</h4>";
								foreach($this->admin_templates as $template) {
				?>
				<div style="margin:5px;height:200px;float:left;text-align:center;width:200px;">
					<input class="template" type="radio" name="screen[template]" style="vertical-align:middle"  value="<?= $template->id ?>"<?php if($screen->template_id==$template->id) echo ' checked'; ?>>
						<a href="<?=ADMIN_URL.'/templates/preview/'.$template->id.'?width=800'?>" class="t-preview"><img style="vertical-align:middle; margin:10px;" src="<?=ADMIN_URL.'/templates/preview/'.$template->id.'?width=150'?>" width="150" height="<?= round(150*$screen->height/$screen->width) ?>" alt="<?= $template->name ?>"/></a>
						<p style="color:#333;"><b><?= $template->name ?></b><br />
						<? if(strlen($template->creator) > 0){ echo "Created by: $template->creator <br />"; } ?>
						<? if(strtotime($template->modified) > 0){echo "Last Updated: " . date("M j, Y", strtotime($template->modified));} ?>
						</p>
					</input>
				</div>
	<?php   } }?>
			</td>
		</tr>
 	</table>
</div>
<br clear="all" />
<!-- End Screen Form General Section -->
