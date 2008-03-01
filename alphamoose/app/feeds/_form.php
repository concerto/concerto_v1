<!-- Beginning Screen Form -->
<?php
   //assuming $this->screen is null or the screen we want to edit
   $screen = $this->screen;
   if($screen->width/$screen->height==(16/9)) 
   {
      $scrimg="screen_169.png";
      $ratio ="16:9";
   }else{
      $scrimg="screen_43.png";
      $ratio ="4:3";
   }
?>
<!-- Begin Screen Form General Section -->
	<h3>General Screen Settings</h3>
	<div style="float:left">
     <img src="<?=ADMIN_BASE_URL?>/images/<?echo $scrimg?>" alt=""
        style="padding-right:15px;" />
	</div>
	<div style="clear:none">
     <table style="clear:none" class='edit_win' cellpadding='6' cellspacing='0'>
       <tr> 
         <td class='firstrow'><h5>Screen Name</h5></td>
         <td class='edit_col firstrow'>
           <input type="text" id="name" name="screen[name]" value="<?=$screen->name?>">
         </td>
       </tr>
       <tr>
         <td><h5>Screen Location</h5></td>
         <td>
           <input type="text" id="desc" name="screen[location]" value="<?=$screen->location?>">
         </td>
       </tr>
       <tr>
         <td><h5>Screen Size (w x h)</h5></td>
         <td>
           <input type="text" id="width" name="screen[width]" size="6" value="<?=$screen->width?>">&nbsp; x &nbsp;
           <input type="text" id="height" name="screen[height]" size="6" value="<?=$screen->height?>">
         </td>
       </tr>
       <tr>
         <td><h5>MAC Address</h5></td>
         <td>
           <input type="text" id="mac_address" name="screen[mac_address]" value="<?=$screen->mac_address?>">
         </td>
       </tr>
       <tr>
         <td><h5>Layout Design</h5></td>
         <td><select name="screen[template]">
             <?php $templates = sql_select('template',array('id','name'));
                   if(is_array($templates))
                     foreach($templates as $template) {
             ?>
                <option value="<?= $template[id] ?>"<?php if($screen->template_id==$template[id]) echo ' SELECTED'; ?>><?=$template[name]?></option>
             <?php   } ?>
             </select></td>
       </tr>
       <tr>
         <td><h5>Owning Group</h5></td>
         <td><select name="screen[group]">
             <?php $groups = sql_select('group',array('id','name'));
                   if(is_array($groups))
                     foreach($groups as $group) {
             ?>
                <option value="<?= $group[id] ?>"<?php if($screen->group_id==$group[id]) echo ' SELECTED'; ?>><?=$group[name]?></option>
             <?php   } ?>
             </select></td>
       </tr>
     </table>
     </div>
	<br clear="all" />
<!-- End Screen Form General Section -->