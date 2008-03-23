<?php
   //assuming $this->user is null or the screen we want to edit
   $content = $this->content;
?>
<!-- Begin Content Form -->
	<div>
     <table class='edit_win' cellpadding='6' cellspacing='0'>
       <tr> 
         <td class='firstrow'><h5>Title</h5></td>
         <td class='edit_col firstrow'>
           <input type="text" id="name" class="extended" name="content[name]" value="<?=$content->name?>">
         </td>
       </tr> 
       <tr>
         <td><h5>Start Date</h5></td>
         <td>
           <input type="text" id="width" name="content[start_time]" value="<?=$content->start_time?>">
         </td>
       </tr>

       <tr>
         <td><h5>End Date</h5></td>
         <td>
           <input type="text" id="width" name="content[end_time]" value="<?=$content->end_time?>">
         </td>
       </tr>

       <tr>
         <td><h5>Duration</h5></td>
         <td>
           <input type="text" size="2" id="width" name="content[duration]" value="<?= $content->duration?$content->end_time:DEFAULT_DURATION?>"> &nbsp;seconds
         </td>
       </tr>

       <tr>
         <td><h5>Feeds<h5></td>
         <td>
           Submit to feed:
           <?php
           foreach ($this->feeds as $arr) {
              list($feed, $value) = $arr;
              echo '<br /><input type="checkbox" name="content[feeds]['.$feed->id.']" value="1"';
              if($checked) echo ' CHECKED';
              echo ' /><label>'.'<a href="'.ADMIN_URL."/feeds/show/$feed->id\">$feed->name</a></label>";
              
           }
           ?>
       </tr>       
     </table>
     </div>
	<br clear="all" />
<!-- End Screen Form General Section -->