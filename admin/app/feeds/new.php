<p><a href="<?echo ADMIN_URL ?>/feeds">Back to Feed Listing</a></p>
<h3>Please fill out all fields to create a new Feed.<h3>

<form method="POST" action="<?=ADMIN_URL?>/feeds/create">
<?php 
        include("_form.php");
?>
<input value="Create Feed" type="submit" name="submit" />
</form>

