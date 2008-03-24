<div id="menuframe">
  <div id="menuframe_padding">

    <div class="logo_box">
	   <div class="logo_box_padding">
	     <center><a href="<?php echo ADMIN_BASE_URL ?>/index.php"><img 
src="<?php echo ADMIN_BASE_URL?>/images/conc_bluebg.gif" alt="Concerto" style="" border="0" 
/></a></center>
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
           echo 'Welcome, <a href="'.ADMIN_URL.'/users/show/'.userName().'">'.firstName().'</a>!';
           ?>
           <br /><br />
           <h3><a href="<?= ADMIN_URL ?>/frontpage/logout">Logout</a></h3>
        <?
          }
        ?>
        </div>
      </div>
    </div>
<?php
if(isLoggedIn() && $_SESSION['user']->id)
        $feeds = sql_select('user','feed.id',false,'LEFT JOIN `user_group` ON user_group.user_id = user.id'.
                            ' LEFT JOIN `feed` ON feed.group_id = user_group.group_id WHERE user.id = '.
                            $_SESSION['user']->id);
if(is_array($feeds)&&count($feeds)>0) {
        foreach($feeds as $feed) {
           $obj = new Feed($feed['id']);
           $modq = $obj->content_list('NULL');
           $num = is_array($modq)?count($modq):0;
           if($num>0)
              $mod_feeds[]='<p><a href="'.ADMIN_URL.'/feeds/show/'.$obj->id.
                 '">'.$obj->name.'</a> ('.$num.')</p>';
        }
}
if(is_array($mod_feeds)&&count($mod_feeds)>0) {
?>
    <div class="alert_box">
	   <div class="alert_box_inset">
        <div class="alert_box_padding">
          <h1>Awaiting Moderation</h1>
<?= join(" \n ", $mod_feeds) ?>
        </div>
      </div>
    </div>
<?
}
?>
  </div>
</div>
