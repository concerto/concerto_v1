<?if($this->canEdit) {?>
<a href="<?=ADMIN_URL.'/screens/edit/'.$this->screen->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Edit Screen</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<a href="<?=ADMIN_URL.'/screens/subscriptions/'.$this->screen->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Manage Subscriptions</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<a href="<?=ADMIN_URL.'/screens/delete/'.$this->screen->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Delete Screen</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>
<?php
}
  if ($this->screen->width/$this->screen->height==(16/9)){
    $scrimg="screen_169.png";
    $ratio ="16:9";
  } else if ($this->screen->width/$this->screen->height==(16/10)) {
    $scrimg="screen_169.png";
    $ratio ="16:10";
  } else{
    $scrimg="screen_43.png";
    $ratio ="4:3";
  }
?>
  <table cellpadding="25" cellspacing="0" width="100%">
    <tr valign="top">
      <td width="250"><img style="float:left; padding-right:10px" src="<?echo ADMIN_BASE_URL?>/images/<?echo $scrimg?>" alt="" /></td>
      <td>
        <h3>Location: <span class="emph"><? echo $this->screen->location?></span></h3>
	     <p></p>
        <h3>Size: <span class="emph"><?php echo $this->screen->width.' x '.$this->screen->height.' ('.$ratio; ?>)</span></h3>
        <h3>Status: 
          <span class="emph">
          <?php if(strtotime($this->screen->last_updated)>strtotime('-1 minutes')) { ?>
            <span style="color:green;">Online</span>
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
      </td>
    </tr>
  </table>
  <h3>Subscriptions</h3>

<?php
	$fields=$this->screen->list_fields();
	if(is_array($fields)) {
 	 foreach ($fields as $field) { 
?>
<div class="roundcont">
  <div class="roundtop"><img src="<? echo ADMIN_BASE_URL ?>/images/wc_tl.gif" alt="" width="6" height="6" class="corner topleft" style="display: none" /></div>
  <div class="roundcont_main">
    <h1><span class="emph"><? echo $field->name ?></span> (Field)</h1>
    <ul>
	   <?php
		$positions = $field->list_positions();
		if($positions) {
		  foreach($positions as $position) {
			$feed = new Feed($position->feed_id);
	   ?>
		  <li><a href="<?=ADMIN_URL.'/feeds/show/'.$feed->id?>"><?=$feed->name?></a></li>
	   <?php
		  }
	        } else echo "<li>(no subscriptions)</li>";
	   ?>
	 </ul>
  </div>
  <div class="roundbottom"><img src="<? echo ADMIN_BASE_URL ?>/images/wc_bl.gif" alt="" width="6" height="6" class="corner botleft" style="display: none" /></div>
</div>

<?php   }
       }else echo "<p>No fields on this template</p>";
?> 

