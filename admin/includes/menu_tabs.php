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
?>
<div id="tabs">
	<div id="tabs-padding" class="tabs_bgimg">
	<?php
	$controlsstuff = isLoggedIn() && (isAdmin() || $_SESSION[user]->controls_afeed() || $_SESSION[user]->controls_ascreen());
	if(isLoggedIn()) { //We will change this!
	?>
	  <ul>
	    <li><a href="<?= ADMIN_URL ?>/frontpage/dashboard" title="Dashboard"><span>Dashboard</span></a></li>
	    <li><a style="margin-left:12px;" href="<?= ADMIN_URL ?>/content/new" title="Add new content to the system"><span>Add Content</span></a></li>
	    <li><a href="<?= ADMIN_URL ?>/browse/" title="Browse all content in the system, sorted by feed"><span>Browse Content</span></a></li>
	    <li><a style="margin-left:12px" href="<?= ADMIN_URL ?>/screens/" title="View and Edit Concerto Screens"><span>Screens</span></a></li>
	<?  if($controlsstuff) { ?>
	    <li><a style="margin-left:12px;" href="<?= ADMIN_URL ?>/users/" title="Browse and edit user profiles"><span>Users</span></a></li>
	<?  } ?>
	    <li><a<?=!$controlsstuff?' style="margin-left:12px;"':''?> href="<?= ADMIN_URL ?>/groups/" title="Browse and user groups"><span>User Groups</span></a></li>
	<? if(isAdmin()){ ?>
	    <li><a style="margin-left:12px;" href="<?= ADMIN_URL ?>/frontpage/admin" title=""><span>Admin</span></a></li>
	    <li><a href="<?= ADMIN_URL ?>/pages" title="Manage informational content pages"><span>Info Pages</span></a></li>
	<? } ?>
	  </ul>
	<?php
	} else {
	?>
		<h1 class="fp">Have a username and password?  If so, you can log into Concerto to the left.</h1>
		<p>Concerto is 100% free to use for all current members of the your community.</p>
	<?php 
	}
	?>
	</div>
</div>
<div style="clear:both;"></div>

