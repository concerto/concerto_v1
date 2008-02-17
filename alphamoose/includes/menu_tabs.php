<div id="tabs">
  <ul>
<?php
 
if(isLoggedIn()) { //We will change this!
?>
    <li><a href="<?= ADMIN_URL ?>/screens/" title="View and Edit Concerto Screens"><span>Screens</span></a></li>
    <li><a href="<?= ADMIN_URL ?>/users/" title="Browse and edit user profiles"><span>Users</span></a></li>
    <li><a href="<?= ADMIN_URL ?>/frontpage/stupid" title=""><span>Don't click here.</span></a></li>

<?php
}
?>
  </ul>
</div>