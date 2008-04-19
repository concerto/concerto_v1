<form method="POST" 
action="<?=ADMIN_URL?>/pages/update/<?=$this->page['id']?>">
<?php 
	include("_form.php");
?>
<input value="Save Changes" type="submit" name="submit" />
</form>
