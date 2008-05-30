<? if ($this->feed->user_priv($_SESSION['user'], "edit")) { ?>
<a href="<?=ADMIN_URL.'/feeds/edit/'.$this->feed->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Edit Feed</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a> 
<a href="<?=ADMIN_URL.'/feeds/delete/'.$this->feed->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Delete Feed</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<? } ?>
<a href="<?=ADMIN_URL.'/browse/show/'.$this->feed->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Browse Feed</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>

<div style="clear:both;height:12px;"></div>
<h3>Feed Name: <span class="emph"><?= $this->feed->name ?></span></h3>
<h3>Group: <span class="emph"><a href="<?=ADMIN_URL.'/groups/show/'.$this->group->id?>"><?= $this->group->name ?></a></span></h3>
<h3>Feed Statistics:</h3>
<p><a href="<?=ADMIN_URL."/browse/show/{$this->feed->id}"?>">Active and Future Content: <?= $this->active_content ?></a></p>
<p><a href="<?=ADMIN_URL."/browse/show/{$this->feed->id}/expired"?>">Expired Content: <?= $this->expired_content ?></a></p>