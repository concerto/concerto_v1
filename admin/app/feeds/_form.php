<!-- Beginning Feed Form -->
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
     </table>
     <br clear="all" />
<!-- End Feed Form -->
