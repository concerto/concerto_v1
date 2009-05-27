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
?><h3>Please fill out all fields to create a new screen.<h3>

<form method="POST" action="<?=ADMIN_URL?>/screens/livecdcreate">
<!-- Beginning Screen Form -->
<?php

 if(isset($this->livecd['width']) && isset($this->livecd['height'])){
		if ($this->livecd['width']/$this->livecd['height']==(16/9)){
			$ratio = "16:9";
			$scrimg="screen_169_on_big.png";
		} else if ($this->livecd['width']/$this->livecd['height']==(16/10)) {
			$ratio = "16:10";
			$scrimg="screen_169_on_big.png";
		} else {
			$ratio = "4:3";
			$scrimg="screen_43_on_big.png";
		}
	}


?>
<!-- Begin LiveCD Form  -->
<h3>Create a LiveCD Screen</h3>
<br />
<div style="text-align:center; width:28%; float:left;">
	<img src="<?=ADMIN_BASE_URL?>/images/<?echo $scrimg?>" height="150" style="margin-right:15px !important;" alt="" />
</div>
<div style="clear:none; width:70%; float:right;">
	<table style="clear:none;" class='edit_win' cellpadding='6' cellspacing='0'>
		<tr>
			<td class='firstrow'><h5>Screen Name</h5></td>
			<td class='edit_col firstrow'>
				<input type="text" id="name" tabindex="1" name="screen[name]" value="">
			</td>
		</tr>
		<tr>
			<td><h5>Screen Location</h5></td>
			<td>
				<input type="text" id="desc" tabindex="2" name="screen[location]" value="">
			</td>
		</tr>
		<tr>
			<td><h5>Screen Size<br />(W x H, in pixels)</h5></td>
			<td>
				<input type="text" id="width" tabindex="3" name="screen[width]" size="6" value="<?=$this->livecd['width']?>">&nbsp; x &nbsp;
				<input type="text" id="height" tabindex="4" name="screen[height]" size="6" value="<?=$this->livecd['height']?>">
			</td>
		</tr>
		<tr>
			<td><h5>MAC Address</h5></td>
			<td>
				<input type="hidden" id="mac_inhex" name="screen[mac_inhex]" value="<?= $this->livecd['mac'] ?>"><?= $this->livecd['mac'] ?>
			</td>
		</tr>
		<tr>
			<td><h5>Owning Group</h5></td>
			<td><select name="screen[group]" tabindex="5">
			<?php 
							 if(is_array($this->groups))
								 foreach($this->groups as $group) {
				 ?>
						<option value="<?= $group->id ?>"><?= $group->name ?></option>
				 <?php   } ?>
				 </select></td>
		</tr>
 	</table>
</div>
<br clear="all" />
<!-- End Live CD Form -->

<input value="Create Screen" type="submit" name="submit" <? if($this->nogroups) echo "disabled=disabled"; ?>/>
</form>
