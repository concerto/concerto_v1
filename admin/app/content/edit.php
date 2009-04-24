<a href="<?=ADMIN_URL.'/users/show/'.$this->user->username ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">View <?=$this->user->firstname?>'s Profile</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>

<form method="POST" action="<?=ADMIN_URL?>/users/update/<?=$this->user->username?>">
<?php 
	include("_form.php");
?>
<input value="Save Changes" type="submit" name="submit" />
</form>