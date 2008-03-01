<?php if(isAdmin()) { ?>
<p><a href="<?=ADMIN_URL.'/users/new'?>">Add new user</a></p>
<? } ?>
<h2>Click on a user to view thier profile.</h2>
<table class="edit_win" cellpadding="6" cellspacing="0">
<?php
foreach($this->users as $user){
?>
  <tr>
    <td<? if (!$notfirst) {$notfirst =1;  echo ' class="firstrow"';} ?>>
    <a href="<?= ADMIN_URL?>/users/show/<? echo $user->username ?>">
    <h1><?= $user->name ?></h1>
    </a>
    <? if($user->admin_privileges) echo "Concerto Administrator" ?>
    </td>
  </tr>

<?php
}
?>
</table>