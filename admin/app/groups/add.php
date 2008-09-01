<h2>Select a user from the RCS-sorted list on the left, and click the "Add" button to add that user to your group. <a href="http://signage.rpi.edu/admin/index.php/pages/show/docs/21#s1"><img class="icon" border="0" src="<?= ADMIN_BASE_URL ?>images/help_button.gif" alt="Extra Help" title="Extra Help" /></a></h2>
<form method="POST" action="<?=ADMIN_URL?>/groups/subscribe/<?=$this->group->id?>">
<select id="user" name="user">
   <option value=""> </option>\n
<?php
if(is_array($this->users))
   foreach($this->users as $user)
      echo "   <option value=\"{$user[username]}\">$user[username] - $user[name]</option>\n";
?>
&nbsp;&nbsp;<input value="Add User to Group" type="submit" name="submit" />
</form>

