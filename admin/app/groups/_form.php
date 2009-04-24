<!-- Beginning Group Form -->
<?php
   //assuming $this->group is null or the group we want to edit
   $screen = $this->group;

?>
     <table style="clear:none" class='edit_win' cellpadding='6' cellspacing='0'>
       <tr> 
         <td class='firstrow'><h5>Group Name</h5></td>
         <td class='edit_col firstrow'>
           <input type="text" id="name" name="group[name]" value="<?=$group->name?>">
         </td>
       </tr>
     </table>
	<br clear="all" />
<!-- End Screen Form -->
