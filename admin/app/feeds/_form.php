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
?><!-- Beginning Feed Form -->
<?php
   //assuming $this->feed is null or the feed we want to edit
   $feed = $this->feed;
?>
     <table style="clear:none" class='edit_win' cellpadding='6' cellspacing='0'>
       <tr> 
         <td class='firstrow'><h5>Feed Name</h5></td>
         <td class='edit_col firstrow'>
           <input type="text" id="name" name="feed[name]" value="<?=$feed->name?>">
         </td>
       </tr>
       <tr> 
         <td class='firstrow'><h5>Description</h5></td>
         <td class='edit_col firstrow'>
           <input type="text" id="description" name="feed[description]" value="<?=$feed->description?>">
         </td>
       </tr>
       <tr>
         <td><h5>Controlling Group</h5></td>
         <td><select name="feed[group]">
                <option value=""<?php if(!isset($feed->group_id)) echo ' SELECTED'; ?>></option>
             <?php $groups = sql_select('group',array('id','name'));
                   if(is_array($groups))
                     foreach($groups as $group) {
             ?>
                <option value="<?= $group[id] ?>"<?php if($feed->group_id==$group[id]) echo ' SELECTED'; ?>><?=$group[name]?></option>
             <?php   } ?>
             </select></td>
       </tr>
      <tr>
         <td><h5>Feed Type</h5></td>
         <td><?php if($feed->type == 1) { echo "Dynamic Feed"; } elseif($feed->type == 4) { echo "Dynamic Data Feed"; } else { ?>
             <select name="feed[type]">
             <?php 
                  $types[0] = "Normal";
                  $types[2] = "Restricted";
                  $types[3] = "Private";
                  foreach($types as $t_id => $t_name) {
             ?>
                <option value="<?= $t_id ?>"<?php if($feed->type==$t_id) echo ' SELECTED'; ?>><?=$t_name?></option>
             <?php   } ?>
             </select> <?php } ?></td>
       </tr>
     </table>
     <br clear="all" />
<!-- End Feed Form -->
