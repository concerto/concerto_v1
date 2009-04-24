<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technolgies Group)
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
 * @author       Web Technologies Group, $Author: mike $
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision: 551 $
 */
?><?php if($this->canEdit) { ?>
<a href="<?=ADMIN_URL.'/pages/new' ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">New Page</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<a href="<?=ADMIN_URL.'/page_categories' ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Manage Categories</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>
<? } ?>

<h2>Click on a page for more information and contents.</h2>
<p>An asterisk (*) represents items that will not show up in the menu.</p>
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
<br /><br /><h1><a href="<?= ADMIN_URL?>/pages/show/<?=$page['path']?>"><?=$page['cat']?></a>
<?php
if($this->canEdit) {
?>
<a href="<?=ADMIN_URL.'/page_categories/edit/'.$page['page_category_id']?>">(edit)</a></h1>
<?php
}
?>
<?php if($this->canEdit) { ?>
   <form action="<?=ADMIN_URL?>/pages/setdefault/<?=$page['path']?>" method="GET">
   Default page: <select name="page">
   <option value=""></option>
<?php
   $pp = sql_select('page',Array('id','name'),"page_category_id LIKE $page[page_category_id]");
   list($cat) = sql_select('page_category','default_page','id = '.$page[page_category_id]);
   if(is_array($pp)) {
   foreach($pp as $lp) {
?>
      <option value="<?=$lp['id']?>"<?=$cat['default_page']==$lp['id']?" selected":""?>><?=$lp['name']?></option>
<?php
   }
}
?>
<input type="submit" name="submit" value="submit" />
</option>
</select>
</form>
<?php
}
?>
<br />
<table class="edit_win" cellpadding="6" cellspacing="0">
<?php
    $prev_cat=$page['cat'];
    $open_table=1;
   }
?>
  <tr><td<? if (!$notfirst) {$notfirst =1;  echo ' class="firstrow"';} ?>>
  <span class="emph"><a href="<?= ADMIN_URL?>/pages/show/<?=$page['path']?>/<?= $page[0] ?>"><?= $page['name'] ?></a> <?=$page['in_menu']?"":"*"?></span></td>
  <td style="text-align:right;">
  <?php if($this->canEdit) { ?>

     <a href="<?=ADMIN_URL?>/pages/edit/<?=$page['id']?>">edit</a> &nbsp;
     <a href="<?=ADMIN_URL?>/pages/delete/<?=$page['id']?>">del</a> &nbsp;
     <strong>
     <a href="<?=ADMIN_URL?>/pages/up/<?=$page['id']?>">&uarr;</a> &nbsp;
     <a href="<?=ADMIN_URL?>/pages/dn/<?=$page['id']?>">&darr;</a>
     </strong>
  </td>
  <?php } ?>
  </tr>
<?php
}
?>
</table>
