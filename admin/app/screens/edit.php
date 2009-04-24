<form method="POST" action="<?=ADMIN_URL?>/screens/update/<?=$this->screen->id?>">
<?php 
	include("_form.php");
?>
<div style="float:left; width:28%;">&nbsp;</div>
<div style="float:right; width:70%;"><br /><input value="Save Changes" type="submit" name="submit" /></div>
<div style="clear:both;"></div>
</form>