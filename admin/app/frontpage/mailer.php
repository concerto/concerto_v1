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
?><h2>Use this form to send mail to users & groups</h2>

<div class="roundcont">
  <div class="roundtop"><span class="rt"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
  <div class="roundcont_main">
  <form action="<?=ADMIN_URL?>/frontpage/sendmail" method="POST">
    <h1>Users</h1>
<?php
      if(is_array($this->users))
         foreach($this->users as $user) {
            echo '<input type="checkbox" name="user[]" value="'.$user->username.'" />' . $user->name . '<br />';
         }

?>
    <br />
    <h1>Groups</h1>
<?php
      if(is_array($this->groups))
         foreach($this->groups as $group) {
            echo '<input type="checkbox" name="group[]" value="'.$group->id.'" />' . $group->name . '<br />';
         }
?>
    <br />
    <h1>Special</h1>
    <input type="checkbox" name="special[]" value="screen" />All Screen Owners<br />
    <input type="checkbox" name="special[]" value="feed" />All Feed Owners<br />
    <input type="checkbox" name="everyone" value="all" /><i>All Users</i><br />
    <br />
    <h1>From</h1>
    <input type="radio" name="from" value="system" checked="checked"> Concerto System (<?= SYSTEM_EMAIL ?>)
    <input type="radio" name="from" value="user"> <?= $this->fromyou ?>
    <br />
    <br />
    <h1>Subject</h1>
    <input type="text" name="subject" value="" />
    <h1>Message</h1>
    <textarea name="message" cols="80" rows="10"></textarea>
    <br />
    <br />
    <input type="submit" value="Send" />
    <br />
    <br />
    <i>Don't worry, only one email will be sent to each person, even if you select them twice.</i>
  </form>
  </div>
  <div class="roundbottom"><span class="rb"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
</div>
