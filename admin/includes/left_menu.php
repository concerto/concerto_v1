<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technologies Group)
 *
 * This program is free software; you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.  You should have received a copy
 * of the GNU General Public License along with this program.
 *
 * @package      Concerto
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */
?><div id="menuframe">
  <div id="menuframe_padding">

    <div class="logo_box">
	   	<div class="logo_box_padding">
	    	<center><a href="<?= ADMIN_URL ?>/pages/show/front"><img 
src="<?php echo ADMIN_BASE_URL?>/images/conc_bluebg.gif" alt="Concerto" style="" border="0" 
/></a></center>
	   	</div>
	  	<div class="logo_box_bot"><img border="0" src="<?= ADMIN_BASE_URL ?>images/logobox_bottom.gif" alt="" /></div>
	 	</div>
    <div class="menu_box">
    	<div class="menu_box_top"><img border="0" src="<?= ADMIN_BASE_URL ?>images/menubox_top.gif" alt="" /></div>
	    <div class="menu_box_padding">
        <? 
         if (!isLoggedIn()) { ?>
         <h2 style="font-size:1.6em;"><a href="<?= ADMIN_URL ?>/frontpage/login">Log In</a></h2>        
	<? } else {
         ?>
	   <?	 echo '<p>Welcome, <a href="'.ADMIN_URL.'/users/show/'.userName().'">'.firstName().'</a>!</p><br />';  ?>
	   	<a href="<?= ADMIN_URL ?>/users/show/<?= userName() ?>" title="View your profile and past submissions">
     <?  if ( isAdmin() ) { ?>
         <img border="0" src="<?=ADMIN_BASE_URL ?>images/user_1337.gif" alt="" />
           <? } elseif ($_SESSION[user]->controls_afeed() || $_SESSION[user]->controls_ascreen()) { ?>
         <img border="0" src="<?=ADMIN_BASE_URL ?>images/user_admin.gif" alt="" />
           <? } else { ?>
         <img border="0" src="<?= ADMIN_BASE_URL ?>images/user_basic.gif" alt="" /> 
           <? } //This closes the non admin or moderator stuff 
           ?>
           
           <h4>View Account</h4></a>
           <br />
           <h3><a href="<?= ADMIN_URL ?>/frontpage/logout" title="Log out of Concerto">Logout</a></h3>
        <?
          }
        ?>
      </div>
      <div class="menu_box_bot"><img border="0" src="<?= ADMIN_BASE_URL ?>images/menubox_bottom.gif" alt="" /></div>
    </div>

<?php
if(isLoggedIn()) {
   $sql = 'SELECT feed_content.feed_id as feed_id, COUNT(content.id) as cnt '.
          'FROM feed_content '.
          'LEFT JOIN content ON feed_content.content_id = content.id '.
          'WHERE feed_content.moderation_flag IS NULL '.
          'GROUP BY feed_content.feed_id;';
   $res = sql_query($sql);

   $more_waiting = 0;
   for($i = 0;$row = sql_row_keyed($res,$i);++$i){
      $count = $row['cnt'];
      $feed = new Feed($row['feed_id']);
      if($feed->user_priv($_SESSION['user'], 'moderate',true)) {
         $mod_feeds[]="<p><a href=\"".ADMIN_URL."/moderate/feed/{$feed->id}\">" . htmlspecialchars($feed->name) . " ({$row['cnt']})</a></p>";
      } else {
         $more_waiting += $row['cnt'];
      }
   }
}
if(isset($mod_feeds) || ($more_waiting && isAdmin())) {
?>
    <div class="alert_box">
	   <div class="alert_box_inset">
        <div class="alert_box_padding">
          <h1><a href="<?=ADMIN_URL?>/moderate">Awaiting Moderation</a></h1>
          <?= isset($mod_feeds) ? join("\n", $mod_feeds) : "Nothing in your feeds." ?>
          <? if ($more_waiting > 0 && isAdmin()) {?>
             <p><a href="<?=ADMIN_URL?>/moderate"><?=$more_waiting?> items in other feeds...</a></p>
          <? } ?>
        </div>
      </div>
    </div>
<?
}
?>
  </div>
</div>
