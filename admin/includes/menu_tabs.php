<div id="tabs">
  <ul>
<?php
 
if(isLoggedIn()) { //We will change this!
?>
    <li><a href="<?= ADMIN_URL ?>/content/new" title="Add new content to the system"><span>Add Content</span></a></li>
    <li><a href="<?= ADMIN_URL ?>/content/" title="Browse all content in the system"><span>View Content</span></a></li>
    <li><a style="margin-left:12px" href="<?= ADMIN_URL ?>/feeds/" title="Browse and moderate feeds"><span>Feeds</span></a></li>
    <li><a href="<?= ADMIN_URL ?>/screens/" title="View and Edit Concerto Screens"><span>Screens</span></a></li>
    <li><a style="margin-left:12px" href="<?= ADMIN_URL ?>/users/show/<?= userName() ?>" title="View your profile and past submissions"><span>My Profile</span></a></li>
<?  if(has_action_auth('users',NULL)) { ?>
    <li><a style="margin-left:12px" href="<?= ADMIN_URL ?>/users/" title="Browse and edit user profiles"><span>Users</span></a></li>
    <li><a href="<?= ADMIN_URL ?>/groups/" title="Browse and user groups"><span>Groups</span></a></li>
<?  } ?>
<? if(false){ ?>
    <li><a style="margin-left:12px" href="<?= ADMIN_URL ?>/frontpage/stupid" title=""><span>Don't click here.</span></a></li>
<? } ?>
<?php
}
?>
  </ul>
</div>