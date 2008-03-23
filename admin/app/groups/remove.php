<form method="POST" action="<?=ADMIN_URL?>/groups/unsubscribe/<?=$this->group->id?>">
<select id="user" name="user">
<option value=\"\"> </option>\n
<?php
if(is_array($this->users))
	foreach($this->users as $user)
		echo "   <option value=\"{$user->username}\"".($_REQUEST['user']==$user->username?" SELECTED":"").">$user->username - $user->name</option>\n";
?>
<input value="Remove User from Group" type="submit" name="submit" />
</form>

