<form method="POST" action="<?=ADMIN_URL?>/groups/unsubscribe/<?=$this->group->id?>">
<select id="user" name="user">
<option value=""> </option>
<?php
if(is_array($this->users))
	foreach($this->users as $user)
		echo "   <option value=\"{$user->username}\"".($_REQUEST['user']==$user->username?" SELECTED":"").">$user->username - $user->name</option>\n";
?>
</select>
<input value="Remove User from Group" type="submit" name="submit" />
</form>

