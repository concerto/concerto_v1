<?php if($this->canEdit) { ?>
<a href="<?=ADMIN_URL.'/pages/new' ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">New Page</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>
<? } ?>

<h2>Click on a page for more information and contents.</h2>
<?php
$prev_cat="";
$open_table=0;
foreach($this->pages as $page){
   if($page['cat']!=$prev_cat) {
      if($open_table) {
?>
</table> 
<?php
     }
?>
<br /><h1><span class="emph"></a><?=$page['cat']?></span></h1>
<table class="edit_win" cellpadding="6" cellspacing="0"> 
<?php
    $prev_cat=$page['cat'];
    $open_table=1;
   }
?>
  <tr><td<? if (!$notfirst) {$notfirst =1;  echo ' class="firstrow"';} ?>>
  <h1 style="float:left"><a href="<?= ADMIN_URL?>/pages/show/<?=$page['path']?>/<?= $page[0] ?>"><?= $page['name'] ?></a></h1>
  <?php if($this->canEdit) { ?>
  <div style="float:right; display:inline">
     <a href="<?=ADMIN_URL?>/pages/edit/<?=$page['id']?>">edit</a> &nbsp;
     <a href="<?=ADMIN_URL?>/pages/delete/<?=$page['id']?>">del</a> &nbsp;
     <strong>
     <a href="<?=ADMIN_URL?>/pages/up/<?=$page['id']?>">&uarr;</a> &nbsp;
     <a href="<?=ADMIN_URL?>/pages/dn/<?=$page['id']?>">&darr;</a>
     </strong>
  </div>
  <?php } ?>
  </td></tr>
<?php
}
?>
</table>