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

