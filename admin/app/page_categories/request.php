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
?><h3>Please fill out all fields to request a new feed.</h3>
<p>If your feed request is approved, we'll be contacting you shortly about setting up a controlling group for the feed.</p>

<form method="POST">
<!-- Beginning Feed Form -->
     <table style="clear:none" class='edit_win' cellpadding='6' cellspacing='0'>
       <tr> 
         <td><h5>Feed Name</h5></td>
         <td class='edit_col'>
           <input type="text" id="name" name="feed[name]" size="30" value="">
         </td>
       </tr>
       <tr> 
         <td class='firstrow'><h5>Organization</h5>
            <p>What campus organization would be responsible for this feed?</p>
         </td>
         <td class='edit_col firstrow'>
           <input type="text" id="name" name="feed[org]" size="30" value="">
         </td>
       </tr>
       <tr> 
         <td class='firstrow'><h5>Description</h5>
            <p>Tell us a little about the feed you'd like to see on the system, and what sort of content you envision in it.</p>
         </td>
         <td class='edit_col firstrow'>
           <textarea id="desc" name="feed[desc]" rows="10" cols="45"></textarea>
         </td>
       </tr>
     </table>
     <br clear="all" />
<!-- End Feed Form -->
<input value="Create Feed" type="submit" name="submit" />
</form>
