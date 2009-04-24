<form method="POST" 
action="<?=ADMIN_URL?>/feeds/update/<?=$this->feed->id?>">
<?php 
	include("_form.php");
?>
<input value="Save Changes" type="submit" name="submit" />
</form>
