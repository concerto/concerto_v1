<a href="<?=ADMIN_URL.'/page_categories/new' ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">New Category</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<a href="<?=ADMIN_URL.'/pages' ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">View Pages</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<div style="clear:both;height:12px;"></div>


<h2>Click on a category for more information</h2>
<table class="edit_win" cellpadding="6" cellspacing="0">
<?php
if($this->categories){
	foreach($this->categories as $cat){
?>
  <tr><td><h1><a href="<?= ADMIN_URL?>/page_categories/show/<?= $cat['id'] ?>"><?= $cat['name'] ?></a></h1></td></tr>
<?php
	}
}
?>
</table>