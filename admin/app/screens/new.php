<p><a href="<?echo ADMIN_URL ?>/screens">Back to Screens Listing</a></p>
<h3>Please fill out all fields to create a new screen.<h3>

<form method="POST" action="<?=ADMIN_URL?>/screens/create">
<?php 
        include("_form.php");
?>
<input value="Create group" type="submit" name="submit" />
</form>

