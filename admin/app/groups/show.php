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
	<h3>Feeds:</h3>
	<table class="edit_win" cellpadding="6" cellspacing="0">
	<?php
	foreach($this->feeds as $feed){
	?>
	  <tr>
	    <td<? if (!$notfirst) {$notfirst =1;  echo ' class="firstrow"';} ?>><h1><a href="<?= ADMIN_URL?>/feeds/show/<?= $feed->id ?>"><?= $feed->name ?></a></h1></td>
	  </tr>
	<? } ?>
	</table>
	<? } ?>

	<? if(is_array($this->screens)&&count($this->screens)>0) { ?>
	<h3>Screens:</h3>
	<?php
	foreach($this->screens as $screen){
	  if($screen->width/$screen->height==(16/9)){
	    $scrimg="screen_169.png";
	    $ratio ="16:9";
	  }else{
	    $scrimg="screen_43.png";
	    $ratio ="4:3";
	  }
	?>
	  <a href="<?echo ADMIN_URL?>/screens/show/<? echo $screen->id ?>">
	    <div class="screenfloat">
	      <img src="<?echo ADMIN_BASE_URL?>/images/<?echo $scrimg?>" alt="" /><br /><br />
	      <h1><? echo $screen->name?></h1>
	      <h2><? echo $screen->location?></h2>
	      <h3><?php echo $screen->width.' x '.$screen->height.' ('.$ratio; ?>)</h3>
	      <?php if(strtotime($screen->last_updated)>strtotime('-1 minutes')) { ?>
		<span style="color:green;font-size:1.3em;font-weight:bold;">Online</span>
	      <?php } else { ?>
		<span style="color:red;font-size:1.3em;font-weight:bold;">Offline</span>
	      <?php } ?>
	    </div>
	  </a>

	<?php
	}
	?>
	<? } ?>




