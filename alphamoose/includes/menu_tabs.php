<div id="tabs">
  <ul>
<?php
 
if(loggedIn()) { //We will change this!
?>
    <li><a href="<?php echo $admin_url ?>/control_screens/" title="Link 1"><span>Control Screens</span></a></li>
    <li><a href="<?php echo $admin_url ?>/manage_content/moderate.php" title="Link 2"><span>Awaiting Moderation (2)</span></a></li>
    <li><a href="<?php echo $admin_url ?>/manage_content/" title="Link 3"><span>Archived Content</span></a></li>
    <li><a href="<?php echo $admin_url ?>/add_content/" title="Link 4"><span>Add Content</span></a></li>
    <li><a href="<?php echo $admin_url ?>/manage_content/moderate.php" title="Link 5"><span>My Submissions</span></a></li>
<?php
}
?>
  </ul>
</div>