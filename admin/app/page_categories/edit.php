<form method="POST" 
action="<?=ADMIN_URL?>/page_categories/update/<?=$this->category['id']?>">
<?php 
	include("_form.php");
?>
<input value="Save Changes" type="submit" name="submit" />
</form>
