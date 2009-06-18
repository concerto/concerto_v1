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
?><!-- Beginning Page Form -->
<?php
   //assuming $this->page is null or the page we want to edit
   $page = $this->page;
?>
     <table style="clear:none" class='edit_win' cellpadding='6' cellspacing='0'>
       <tr> 
         <td class='firstrow'><h5>Page Name</h5></td>
         <td class='edit_col firstrow'>
           <input type="text" id="name" name="page[name]" value="<?=$page['name']?>">
         </td>
       </tr>
       <tr>
         <td><h5>Category</h5></td>
         <td><select name="page[category]">
                <option value=""<?php if(!isset($page['page_category_id'])) echo ' SELECTED'; ?>></option>
                <?php $cats = sql_select('page_category',array('id','name'));
                   if(is_array($cats))
                     foreach($cats as $cat) {
             ?>
                <option value="<?= $cat['id'] ?>"<?php if($page['page_category_id']==$cat['id']) echo ' SELECTED'; ?>><?=$cat['name']?></option>
             <?php   } ?>
             </select></td>
       </tr>
       <tr>
         <td><h5>Show in menu</h5></td>
         <td><select name="page[in_menu]">
            <option value="0"<?=$page['in_menu']?"":" selected"?>>No</option>
            <option value="1"<?=$page['in_menu']?" selected":""?>>Yes</option>
          </select></td>
       </tr>
       <tr>
         <td><h5>Show feedback form</h5></td>
         <td><select name="page[feedback]">
            <option value="0"<?=$page['get_feedback']?"":" selected"?>>No</option>
            <option value="1"<?=$page['get_feedback']?" selected":""?>>Yes</option>
          </select></td>
       </tr>
       <tr> 
         <td class='firstrow'><h5>Page Content (HTML)</h5></td>
         <td class='edit_col firstrow'>
           <textarea cols="80" rows="25" name="page[content]"><?=$page['content']?></textarea>
         </td>
       </tr>
     </table>
     <br clear="all" />
<!-- End Page Form -->
