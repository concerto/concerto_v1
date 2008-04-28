<?php if(isAdmin()) { ?>
<a href="<?=ADMIN_URL.'/feeds/new' ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">New Feed</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<? } ?>
<a href="<?=ADMIN_URL.'/feeds/request' ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Request a Feed</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>

<h2>Click on a feed for more information and contents.</h2>
<table class="edit_win" cellpadding="6" cellspacing="0">
<?php
if($this->feeds){
	foreach($this->feeds as $feed){
?>
  <tr><td<? if (!$notfirst) {$notfirst =1;  echo ' class="firstrow"';} ?>><h1><a href="<?= ADMIN_URL?>/feeds/show/<?= $feed->id ?>"><?= $feed->name ?></a></h1></td></tr>
<?php
	}
}
?>
</table>