<p><a href="<?echo ADMIN_URL ?>/users">Back to Users Listing</a>
<?php if (isAdmin() || ($this->user->username == $_SESSION['user']->username)) {?>
 | <a href="<?echo ADMIN_URL ?>/users/edit/<?echo $this->user->username ?>"> Edit <?=$this->user->firstname?>'s profile
</a>
<?php } ?>
</p>
<h3>Username:</h3>
  <p><? echo $this->user->username?></p>
<h3>Groups:</h3>
<ul>
<?php
  if(count($this->groups)<1)
     echo '<li><em>none</em></li>';
  foreach($this->groups as $group)
     echo '<li>'.$group.'</li>';
?>
</ul>
<h3>Contact:</h3>
  <p><a href="mailto:<?php echo $this->user->email?>"><?php echo $this->user->email?></a></p>