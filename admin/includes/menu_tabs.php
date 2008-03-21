<div id="tabs">
  <ul>
<?php
 
if(isLoggedIn()) { //We will change this!
?>
    <li><a href="<?= ADMIN_URL ?>/content/" title="Add and browse content"><span>Content</span></a></li>
    <li><a href="<?= ADMIN_URL ?>/feeds/" title="Browse and moderate feeds"><span>Feeds</span></a></li>
    <li><a href="<?= ADMIN_URL ?>/screens/" title="View and Edit Concerto Screens"><span>Screens</span></a></li>
    <li><a href="<?= ADMIN_URL ?>/users/" title="Browse and edit user profiles"><span>Users</span></a></li>
    <li><a href="<?= ADMIN_URL ?>/groups/" title="Browse and user groups"><span>Groups</span></a></li>
    <li><a style="margin-left:10px" href="<?= ADMIN_URL ?>/frontpage/stupid" title=""><span>Don't click here.</span></a></li>

<?php
}
?>
  </ul>
</div>