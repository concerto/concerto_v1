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
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */
?><?php if(isAdmin()) { ?>
<a href="<?=ADMIN_URL.'/feeds/new' ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">New Feed</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<? } else { ?>
<a href="<?=ADMIN_URL.'/feeds/request' ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Request a Feed</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a>
<? } ?>
<div style="clear:both;height:12px;"></div>
<h2>Click on a feed for more information. <a href="<?= ADMIN_URL ?>/pages/show/docs/23"><img class="icon" border="0" src="<?= ADMIN_BASE_URL ?>images/help_button.gif" alt="Extra Help" title="Extra Help" /></a></h2>
<table class="edit_win" cellpadding="6" cellspacing="0">
<?php
if($this->feeds){
	foreach($this->feeds as $feed){
?>
  <tr><td>
    <h1><a href="<?= ADMIN_URL?>/feeds/show/<?= $feed->id ?>"><?= $feed->name ?></a></h1>
    <? if (strlen($feed->description)>0) { ?>
      <p><?= $feed->description ?></p>
    <? } ?>
  </td></tr>
<?php
	}
}
?>
</table>
