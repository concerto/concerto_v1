<script type="text/javascript"><!--
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
				<input type="text" id="latitude" name="screen[latitude]" size="8" value="<?=$screen->latitude?>">&nbsp; , &nbsp;
				<input type="text" id="longitude" name="screen[longitude]" size="8" value="<?=$screen->longitude?>">
			</td>
		</tr>
		<tr>
			<td><h5>Screen Size<br />(W x H, in pixels)</h5></td>
			<td>
				<input type="text" id="width" name="screen[width]" size="6" value="<?=$screen->width?>">&nbsp; x &nbsp;
				<input type="text" id="height" name="screen[height]" size="6" value="<?=$screen->height?>">
			</td>
		</tr>
		<tr>
			<td><h5>MAC Address</h5></td>
			<td>
				<input type="text" id="mac_inhex" name="screen[mac_inhex]" value="<?=$screen->mac_inhex?>">
			</td>
		</tr>
		<tr>
			<td><h5>Layout Design</h5></td>
			<td>
				<?php
							if(is_array($this->avail_templates))
								foreach($this->avail_templates as $template) {
				?>
				<input class="template" type="radio" name="screen[template]" style="vertical-align:middle"  value="<?= $template->id ?>"<?php if($screen->template_id==$template->id) echo ' checked'; ?>>
					<a href="<?=ADMIN_URL.'/templates/preview/'.$template->id.'?width=800'?>" class="t-preview"><img style="vertical-align:middle; margin:10px;" src="<?=ADMIN_URL.'/templates/preview/'.$template->id.'?width=150'?>" width="150" height="<?= round(150*$screen->height/$screen->width) ?>" alt="<?= $template->name ?>"/></a>
					<?= $template->name ?><br />
					<? if(strlen($template->creator) > 0){ echo "Created by: $template->creator "; } ?>
					<? if(strtotime($template->modified) > 0){echo "Last Updated: " . date("M j, Y", strtotime($template->modified));} ?>
				</input><br />
	<?php   } ?>
	
				<?php
							if(isAdmin() && is_array($this->admin_templates)){
							echo "<h4>Hidden Templates</h4>";
								foreach($this->admin_templates as $template) {
				?>
				<input class="template" type="radio" name="screen[template]" style="vertical-align:middle"  value="<?= $template->id ?>"<?php if($screen->template_id==$template->id) echo ' checked'; ?>>
					<a href="<?=ADMIN_URL.'/templates/preview/'.$template->id.'?width=800'?>" class="t-preview"><img style="vertical-align:middle; margin:10px;" src="<?=ADMIN_URL.'/templates/preview/'.$template->id.'?width=150'?>" width="150" height="<?= round(150*$screen->height/$screen->width) ?>" alt="<?= $template->name ?>"/></a>
					<?= $template->name ?><br />
					<? if(strlen($template->creator) > 0){ echo "Created by: $template->creator "; } ?>
					<? if(strtotime($template->modified) > 0){echo "Last Updated: " . date("M j, Y", strtotime($template->modified));} ?>
				</input><br />
	<?php   } }?>
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
		 	<td><h5>Display On Time</h5><p>What time should the system turn on the screen? <strong>Please specify hh:mm in 24-hour time</strong>, e.g. 18:00 for 6:00 pm, 00:00 for the very beginning of the day, or 23:59 for the end of the day.</p></td>
		 	<td><input type="text" name="screen[time_on]" value="<?=$screen->time_on?>" />
		 	</td>
	 	</tr>
	 	<tr>
		 	<td><h5>Display Off Time</h5><p>What time should the system turn off the screen? <strong>Please specify hh:mm in 24-hour time.</strong></p></td>
		 	<td><input type="text" name="screen[time_off]" value="<?=$screen->time_off?>" />
		 	</td>
	 	</tr>
<? } ?>
 	</table>
</div>
<br clear="all" />
<!-- End Screen Form General Section -->
