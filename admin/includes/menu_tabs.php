<div id="tabs">
  <ul>
<?php
$controlsstuff = isLoggedIn() && (isAdmin() || $_SESSION[user]->controls_afeed() || $_SESSION[user]->controls_ascreen());
if(isLoggedIn()) { //We will change this!
?>
    <li><a href="<?= ADMIN_URL ?>/content/new" title="Add new content to the system"><span>Add Content</span></a></li>
    <li><a href="<?= ADMIN_URL ?>/content/" title="Browse all content in the system"><span>View Content</span></a></li>
    <li><a style="margin-left:12px" href="<?= ADMIN_URL ?>/feeds/" title="Browse and moderate feeds"><span>Feeds</span></a></li>
    <li><a href="<?= ADMIN_URL ?>/screens/" title="View and Edit Concerto Screens"><span>Screens</span></a></li>
    <li><a style="margin-left:12px" href="<?= ADMIN_URL ?>/users/show/<?= userName() ?>" title="View your profile and past submissions"><span>My Account</span></a></li>
<?  if($controlsstuff) { ?>
    <li><a style="margin-left:12px" href="<?= ADMIN_URL ?>/users/" title="Browse and edit user profiles"><span>Users</span></a></li>
<?  } ?>
    <li><a<?=!$controlsstuff?' style="margin-left:12px"':''?> href="<?= ADMIN_URL ?>/groups/" title="Browse and user groups"><span>User Groups</span></a></li>
<? if(isAdmin()){ ?>
    <li><a style="margin-left:12px" href="<?= ADMIN_URL ?>/frontpage/admin" title=""><span>Admin</span></a></li>
    <li><a href="<?= ADMIN_URL ?>/pages" title="Manage informational content pages"><span>Info Pages</span></a></li>
<? } ?>
<?php
}
?>
  </ul>
</div>