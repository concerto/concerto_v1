<img src="<?=ADMIN_URL?>/templates/preview/<?=$this->template['id']?>" />
   <div>
   <form method="POST" action="<?=ADMIN_URL?>/screens/subscribe">
     <table class='edit_win' cellpadding='6' cellspacing='0'>
<?php
foreach($this->screen->list_fields() as $field) {
?>
       <tr>
         <td<? if (!$notfirst) echo ' class="firstrow"'; ?>>
            <h5><?=$field->name?></h5>
         </td>
         <td class="edit_col<? if (!$notfirst) {$notfirst =1;  echo ' firstrow';} ?>">
<?php
foreach($this->feeds as $feed) {
              echo '<br /><select id="content[feeds]['.$feed->id.']">';
              echo '<option value=0'.($value==0?' selected':'').'>Never</option>';
              echo '<option value=33'.($value<=33&&$value>0?' selected':'').'>Sometimes</option>';
              echo '<option value=66'.($value<=66&&$value>33?' selected':'').'>Moderately</option>';
              echo '<option value=100'.($value<=66&&$value>33?' selected':'').'>Very Often</option>';
              echo '</select> <label> Draw content from <a href="'.ADMIN_URL."/feeds/show/$feed->id\">$feed->name</a>";
}
?>
         </td>
       </tr>
<?php
}
?>
     </table>
   </form>
   </div>