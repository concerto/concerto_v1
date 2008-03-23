<?php if (isAdmin()) {?>
<a href="<?=ADMIN_URL.'/screens/new' ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">New Screen</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>
<?php } ?>
<h2>Click on a screen for more information.</h2>
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
    <div class="screenfloat"><div class="screenfloat_padding">
      <img src="<?echo ADMIN_BASE_URL?>/images/<?echo $scrimg?>" alt="" /><br /><br />
      <h1><? echo $screen->name?></h1>
      <h2><? echo $screen->location?></h2>
      <h3><?php echo $screen->width.' x '.$screen->height.' ('.$ratio; ?>)</h3>
      <?php if(strtotime($screen->last_updated)>strtotime('-1 minutes')) { ?>
        <span style="color:green;font-size:1.3em;font-weight:bold;">Online</span>
      <?php } else { ?>
        <span style="color:red;font-size:1.3em;font-weight:bold;">Offline</span>
      <?php } ?>
    </div></div>
  </a>

<?php
}
?>
