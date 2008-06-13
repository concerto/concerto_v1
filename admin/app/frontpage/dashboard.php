<div class="roundcont">
  <div class="roundtop"><img src="<? echo ADMIN_BASE_URL ?>images/wc_tl.gif" alt="" width="6" height="6" class="corner topleft" style="display: none" /></div>
  <div class="roundcont_main">
    <h1>Content Submission Guidelines</h1>
    <p>Pay careful attention to the submission guidelines for graphical content <a href="<?= ADMIN_BASE_URL ?>pages/show/docs/1">here</a>.</p>
  </div>
  <div class="roundbottom"><img src="<? echo ADMIN_BASE_URL ?>images/wc_bl.gif" alt="" width="6" height="6" class="corner botleft" style="display: none" /></div>
</div>
<div class="roundcont">
  <div class="roundtop"><img src="<? echo ADMIN_BASE_URL ?>images/wc_tl.gif" alt="" width="6" height="6" class="corner topleft" style="display: none" /></div>
  <div class="roundcont_main">
    <h1>Did You Know?</h1>
    <p>You can check out the help and support center for Concerto <a href="<?= ADMIN_BASE_URL ?>pages/show/docs/">here</a>.  New updates will be continuously added!</p>
  </div>
  <div class="roundbottom"><img src="<? echo ADMIN_BASE_URL ?>images/wc_bl.gif" alt="" width="6" height="6" class="corner botleft" style="display: none" /></div>
</div>
<div class="roundcont">
  <div class="roundtop"><img src="<? echo ADMIN_BASE_URL ?>images/wc_tl.gif" alt="" width="6" height="6" class="corner topleft" style="display: none" /></div>
  <div class="roundcont_main">
    <h1>Operational Status</h1>
    <p>Content on Concerto will currently be shown on the following displays around RPI'<!--'-->s Troy campus:</p>
    <br />
    <table class="edit_win" cellpadding="6" cellspacing="0">
<?php
foreach($this->screens as $screen) {
   if($screen->width/$screen->height==(16/9)){
      $scrimg="screen_169_sm.png";
      $ratio ="16:9";
   } else if ($screen->width/$screen->height==(16/10)) {
      $scrimg="screen_169_sm.png";
      $ratio ="16:10";
   }else{
      $scrimg="screen_43_sm.png";
      $ratio ="4:3";
   }

?>
      <tr valign="middle">
      <?php if(strtotime($screen->last_updated)>strtotime('-1 minutes')) {
         $image = "images/check_icon.gif";
         $status = "Online";
      } else {
         $image = "images/ex_icon.gif";
         $status = "Offline";
      } ?>
<td class="icon" style="width:95px;"><img class="icon" src="<?= ADMIN_BASE_URL . $image ?>" alt="Screen <?=$status?>" />
<div style="display:inline; margin-left:12px;width:50px; text-align:center">
<a href="<?=url_for('screens','show',$screen->id)?>"><img class="icon" src="<?= ADMIN_BASE_URL ?>images/<?=$scrimg?>" alt="" /></a>
</div></td>
<td><span class="emph"><?=$screen->name?></span>, a <?=$screen->width.'x'.$screen->height?> display in <b><?=$screen->location?></b></td>
      </tr>
<?php
}
?>
    </table>
  </div>
  <div class="roundbottom"><img src="<? echo ADMIN_BASE_URL ?>images/wc_bl.gif" alt="" width="6" height="6" class="corner botleft" style="display: none" /></div>
</div>
