<div id="menuframe">
  <div id="menuframe_padding">
    <div class="logo_box">
	   <div class="logo_box_padding">
	     <center><a href="<?php echo ADMIN_BASE_URL ?>/index.php"><img 
src="<?php echo ADMIN_BASE_URL?>/images/conc_bluebg.gif" style="" border="0" /></a></center>
	   </div>
	 </div>
    <div class="menu_box">
	   <div class="menu_box_inset">
        <div class="menu_box_padding">
        <? 
         if (!isLoggedIn()) { ?>
         <h2><a href="<?= ADMIN_URL ?>/frontpage/login">Login</a></h2>        
	<? } else {
         ?>
	   <?
           if ( isAdmin() ) { ?>
         <img src="<?=ADMIN_BASE_URL ?>/images/user_admin.gif" /><br /><br />
           <? } else { ?>
         <img src="<?= ADMIN_BASE_URL ?>/images/user_basic.gif" /><br /><br /> 
           <? } //This closes the non admin or moderator stuff 
           echo "Welcome, " . firstName() . "!";
           ?>
           <br /><br />
           <h3><a href="<?= ADMIN_URL ?>/frontpage/logout">Logout</a></h3>
        <?
          }
        ?>
        </div>
      </div>
    </div>
  </div>
</div>
