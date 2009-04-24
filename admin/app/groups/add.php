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
?><h2>Select a user from the RCS-sorted list on the left, and click the "Add" button to add that user to your group. <a href="<?= ADMIN_URL ?>/pages/show/docs/21#s1"><img class="icon" border="0" src="<?= ADMIN_BASE_URL ?>images/help_button.gif" alt="Extra Help" title="Extra Help" /></a></h2>
<form method="POST" action="<?=ADMIN_URL?>/groups/subscribe/<?=$this->group->id?>">
<select id="user" name="user">
   <option value=""> </option>
<?php
if(is_array($this->users))
   foreach($this->users as $user)
      echo "   <option value=\"{$user[username]}\">$user[username] - $user[name]</option>\n";
?>
</select>
&nbsp;&nbsp;<input value="Add User to Group" type="submit" name="submit" />
</form>

