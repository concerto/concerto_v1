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
?><form action="<?=ADMIN_URL?>/moderate/post" method="post">
<input type="hidden" name="feed_id" value="<?=$this->feed->id?>" />
<input type="hidden" name="content_id" value="<?=$this->content->id?>" />
<input type="hidden" name="action" value="<?=$this->args[1]?>" />
<? if($this->args[1]=="approve") { ?>
<h1>Duration:</h1>
<p><span class="mod_confirm" title="Duration (seconds)"><input type="text" name="duration" value="<?=$this->content->get_duration($this->feed)/1000?>" size="2" /></span></p>
<? } else { ?>
<h1>Reason for Rejection:</h1>
<p>
<select name="information">
<?
$choices = array("Your content is not applicable to my feed.",
                 "Your content is too hard to read.",
                 "Your content is redundant.",
                 "Your content is inappropriate.");
foreach($choices as $choice) {
?>
<option value="<?= $choice ?>"><?= $choice ?></option>
<? } ?>
</select>
</p>
<? } ?>
<h1>Additional Message to Send to Submitter:</h1>
<p><textarea name="notification" rows="3" cols="30"></textarea></p>
<? if($this->args[4] != "ajax") { ?>
<input type="submit" value="Submit" />
<? } else { ?>
<input type="hidden" name="ajax" value="1" />
<? } ?>
</form>
