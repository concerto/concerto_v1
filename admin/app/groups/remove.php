<p><a href="<?echo ADMIN_URL ?>/groups">Back to Group Listing</a>
 | <a href="<?echo ADMIN_URL ?>/groups/show/<?= $this->group->id ?>"> Back to <?=$this->group->name?>
</a>
</p>

<form method="POST" action="<?=ADMIN_URL?>/groups/unsubscribe/<?=$this->group->id?>">
<select id="user" name="user">
<option value=\"\"> </option>\n
<?php
if(is_array($this->users))
	foreach($this->users as $user)
		echo "   <option value=\"{$user->username}\"".($_REQUEST['user']==$user->username?" SELECTED":"").">$user->username - $user->name</option>\n";
?>
<input value="Remove user" type="submit" name="submit" />
</form>

