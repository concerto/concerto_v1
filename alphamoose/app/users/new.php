<p><a href="<?echo ADMIN_URL ?>/users">Back to Users Listing</a></p>
<h3>Please fill out all fields to create a new user.<h3>

<form method="POST" action="<?=ADMIN_URL?>/users/create">
<?php 
	include("_form.php");
?>
<input value="Save Changes" type="submit" name="submit" />
</form>