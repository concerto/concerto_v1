<p><a href="<?echo ADMIN_URL ?>/groups">Back to Group Listing</a>
 | <a href="<?echo ADMIN_URL ?>/groups/show/<?= $this->group->id ?>"> Back to <?=$this->group->name?>
</a>
</p>

<form method="POST" action="<?=ADMIN_URL?>/groups/subscribe/<?=$this->group->id?>">
<select id="user" name="user">
   <option value=""> </option>\n
<?php
if(is_array($this->users))
	foreach($this->users as $user)
		echo "   <option value=\"{$user[username]}\">$user[username] - $user[name]</option>\n";
?>
<input value="Add user" type="submit" name="submit" />
</form>

