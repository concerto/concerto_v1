<div id="menuframe">
  <div id="menuframe_padding">
    <div class="logo_box">
	   <div class="logo_box_padding">
	     <center><a href="<?php echo $admin_url ?>/index.php"><img src="<?php echo $admin_url?>/images/ds_logo.jpg" style="" width="112" height="112" border="0" /></a></center>
	   </div>
	 </div>
    <div class="menu_box">
	   <div class="menu_box_inset">
        <div class="menu_box_padding">
        <? 
         if (!loggedIn()) { ?>
         <h2><a href="<?php echo $admin_url ?>/login">Login</a></h2>
        
	      <? } 
         else {
		     echo "Welcome, <strong>" . userName() . "</strong>!"; ?>
         <h3><a href="<?php echo $admin_url ?>/login?logout">Logout</a></h3>
         <?
           if ( isAdmin() ) { ?>
             <h2>Admin/Moderator</h2>
           <? } else { ?>
	   	    <h2>Regular User</h2>
           <? } //This closes the non admin or moderator stuff ?>
           <h3><a href="?act=listUsers">List Users</a></h3>    
        <?
          }
        ?>
        </div>
      </div>
    </div>
  </div>
</div>
