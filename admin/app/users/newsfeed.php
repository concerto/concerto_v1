<script type="text/javascript"><!--
$(function()
{
   $("#news_expand").data('items',5);
   
   $("#news_expand").click(function(event) {
         $.post("<?= ADMIN_URL ?>/users/notifications", {'start': $("#news_expand").data('items'), 'num': 5}, function(data) {
               var x = $("<div>"+data+"</div>");
               $("#news_expand").before(x.hide());
               x.slideDown("slow");
               $("#news_expand").data('items',$("#news_expand").data('items')+5);
            });
         return false;
      });
 }); 
//--></script>


<div class="roundcont newsfeed">
  <div class="roundtop"><span class="rt"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
  <div class="roundcont_main">
    <div style="text-align:right; float:right; width:200px;">
    <? $num = count($this->notifications) ?>
    <? if($this->page > 0) {?>
       <span class="prev">
         <a href="<?= ADMIN_URL ?>/users/newsfeed/<?= userName() ?>/<?= $this->page - 1?>">&lt; prev</a>
       </span>
    <? } ?>
    <? if($num>1) {?>
       <span class="next">
         <a href="<?= ADMIN_URL ?>/users/newsfeed/<?= userName() ?>/<?= $this->page + 1?>">next &gt;</a>
       </span>
    <? } ?>
    </div>
    <h1>News Feed</h1>
<? if ($num>0) { ?>
    <h2>Showing items <?= $this->start+1 ?> to <?= $this->start + count($this->notifications) ?> of [#] total notifications</h2>
    <?php 
    if(is_array($this->notifications)) {
       foreach($this->notifications as $newsfeed) {
    ?>
    	<p class="<?= $newsfeed->type ?>_<?= $newsfeed->msg ?>"><?= $newsfeed->text ?><span class="datesub"><?= date('M j', $newsfeed->timestamp) ?></span>
      </p><?php
       }
    }
    ?>
<? } else { ?>
    <h2>No more items to display.</h2>
<? } ?>
  </div>
  <div class="roundbottom"><span class="rb"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
</div>
