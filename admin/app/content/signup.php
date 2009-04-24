<h2>Welcome to Concerto!</h2>
<h3>Please enter some information about yourself to get started.</h3>
<form method="POST" action="<?=ADMIN_URL?>/users/create">
<?php
   include('_form.php');
?>
<input type="submit" />
</form>