<?php if (isAdmin()) {?>
<a href="<?=ADMIN_URL.'/screens/new' ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">New Screen</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>
<?php } ?>
<h2>Click on the name of a screen to view its details. <a href="http://signage.rpi.edu/admin/index.php/pages/show/docs/19#s1"><img class="icon" border="0" src="<?= ADMIN_BASE_URL ?>images/help_button.gif" alt="Extra Help" title="Extra Help" /></a></h2>
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
