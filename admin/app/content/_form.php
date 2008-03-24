<?php
   //assuming $this->user is null or the screen we want to edit
   $content = $this->content;
?>
<!-- Begin Content Form -->
	<div>
     <table class='edit_win' cellpadding='6' cellspacing='0'>
       <tr> 
         <td class='firstrow'><h5>Title</h5><p>Enter some words that describe this piece of content to others.</p></td>
         <td class='edit_col firstrow'>
           <input type="text" id="name" class="extended" name="content[name]" value="<?=$content->name?>">
         </td>
       </tr> 
       <tr>
         <td><h5>Start Date</h5><p>When should this piece of content start to be displayed on Concerto?</p></td>
         <td>
           <input type="text" id="start_date" name="content[start_time]" value="<?=$content->start_time?>">
         </td>
       </tr>

       <tr>
         <td><h5>End Date</h5><p>When should this piece of content expire?  This might be the date of the event you are advertising.</p></td>
         <td>
           <input type="text" id="end_date" name="content[end_time]" value="<?=$content->end_time?>">
         </td>
       </tr>

       <tr>
         <td><h5>Duration</h5><p>For how long should this piece of content be displayed on a screen?</p></td>
         <td>
           <input type="text" size="2" id="width" name="content[duration]" value="<?= $content->duration?$content->end_time:DEFAULT_DURATION?>"> &nbsp;seconds
         </td>
       </tr>

       <tr>
         <td><h5>Feeds</h5><p>In which content categories would this content fit the best?  (You may select more than one.)</p></td>
         <td>
           Submit to Feed:
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
