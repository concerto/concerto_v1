<p><a href="<?echo ADMIN_URL ?>/groups">Back to Group Listing</a></p>
<h3>Please fill out all fields to create a new group.<h3>

<form method="POST" action="<?=ADMIN_URL?>/groups/create">
<?php 
        include("_form.php");
?>
<input value="Create group" type="submit" name="submit" />
</form>

