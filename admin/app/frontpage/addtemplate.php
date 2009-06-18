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
?><h3>Please upload a template file and the descriptor file to add a new template.</h3>

<form enctype="multipart/form-data" method="POST" action="<?=ADMIN_URL?>/frontpage/createtemplate">
<!-- Beginning Template Form -->
     <table style="clear:none" class='edit_win' cellpadding='6' cellspacing='0'>
       <tr> 
         <td class='firstrow'><h5>Template Image</h5></td>
         <td class='edit_col firstrow'>
           <input type="file" id="image" name="template[image]">
         </td>
       </tr>
      <tr>
         <td><h5>Template Descriptor</h5></td>
         <td>
           <input type="file" id="descriptor" name="template[descriptor]">
         </td>
       </tr>
     </table>
     <br clear="all" />
<!-- End Feed Form -->

<input value="Add Template" type="submit" name="submit" />
</form>
