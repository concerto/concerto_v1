<form method="POST" action="<?=ADMIN_URL?>/users/update/<?=$this->user->username?>">
<?php 
	include("_form.php");
?>
<input value="Save Changes" type="submit" name="submit" />
</form>