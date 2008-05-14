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
         <img src="<?=ADMIN_BASE_URL ?>images/user_1337.gif" alt="" /><br /><br />
           <? } elseif ($_SESSION[user]->controls_afeed() || $_SESSION[user]->controls_ascreen()) { ?>
         <img src="<?=ADMIN_BASE_URL ?>images/user_admin.gif" alt="" /><br /><br />
           <? } else { ?>
         <img src="<?= ADMIN_BASE_URL ?>images/user_basic.gif" alt="" /><br /><br /> 
           <? } //This closes the non admin or moderator stuff 
           echo '<p>Welcome, <a href="'.ADMIN_URL.'/users/show/'.userName().'">'.firstName().'</a>!</p>';
           ?>
           
           <h3><a href="<?= ADMIN_URL ?>/frontpage/logout">Logout</a></h3>
        <?
          }
        ?>
        </div>
      </div>
    </div>
        <? if (!isLoggedIn()) { ?>
        <center><img src="<?= ADMIN_BASE_URL ?>images/login_pointer.gif" alt="Login Above" /></center>
        <? } ?>

<?php
$sql = "SELECT feed_content.feed_id as feed_id, COUNT(content.id) as cnt
        FROM feed_content
        LEFT JOIN content ON feed_content.content_id = content.id
        WHERE feed_content.moderation_flag IS NULL
        GROUP BY feed_content.feed_id;";
$res = sql_query($sql);

for($i = 0;$row = sql_row_keyed($res,$i);++$i){
    $count = $row['cnt'];
    $feed = new Feed($row['feed_id']);
    if($feed->user_priv($_SESSION['user'], 'moderate'))
        $mod_feeds[]="<p><a href=\"".ADMIN_URL."/moderate/feed/{$feed->id}\">{$feed->name} ({$row['cnt']})</a></p>";
}

if(isset($mod_feeds)) {
?>
    <div class="alert_box">
	   <div class="alert_box_inset">
        <div class="alert_box_padding">
          <h1><a href="<?=ADMIN_URL?>/moderate">Awaiting Moderation</a></h1>
          <?= join("\n", $mod_feeds) ?>
        </div>
      </div>
    </div>
<?
}
?>
  </div>
</div>
