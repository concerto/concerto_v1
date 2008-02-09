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
           <input type="text" id="title" name="title" value="<?=$screen->name?>">
         </td>
       </tr>
       <tr>
         <td><h5>Screen Description</h5></td>
         <td>
           <input type="text" id="title" name="desc" value="<?=$screen->location?>">
         </td>
       </tr>
       <tr>
         <td><h5>MAC Address</h5></td>
         <td>
           <input type="text" id="mac_address" name="desc" value="<?=$screen->mac_address?>">
         </td>
       </tr>
       <tr>
         <td><h5>Layout Design</h5></td>
         <td><select name="layout">
             <option value="1">Blue Swoosh</option>
             </select></td>
       </tr>
     </table>
     </div>
	<br clear="all" />
<!-- End Screen Form General Section -->