<p><a href="<?echo ADMIN_URL ?>/users">Back to Users Listing</a>
 | <a href="<?echo ADMIN_URL ?>/users/show/<?= $this->user->username ?>"> View <?=$this->user->firstname?>'s Profile
</a>
</p>



<form method="POST" action="<?=ADMIN_URL?>/users/update/<?=$this->user->username?>">
<?php 
	include("_form.php");
?>
<input value="Save Changes" type="submit" name="submit" />
</form>