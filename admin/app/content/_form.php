<?php
   //assuming $this->user is null or the screen we want to edit
   $content = $this->content;
?>
<!-- Begin Content Form -->
	<div>
     <table class='edit_win' cellpadding='6' cellspacing='0'>
       <tr> 
         <td class='firstrow'><h5>Title</h5><p>Enter some words that describe this piece of content to others.</p></td>
         <td colspan="2" class='edit_col firstrow'>
           <input type="text" id="name" class="extended" name="content[name]" value="<?=$content->name?>">
         </td>
       </tr> 
       <tr>
         <td><h5>Start Date</h5><p>When should this piece of content start to be displayed on Concerto?</p></td>
         <td>
           <input type="text" id="content_start_date" class="start_date" name="content[start_date]" value="<?=$content->start_time?>">
           <p id="start_time_msg">Starting at the beginning of the day (12:00am)</p>
         </td>
         <td width="30%" style="text-align:right;"><a href="#" onclick = "this.parentNode.getElementsByTagName('select')[0].style.display='inline'; document.getElementById('start_time_msg').style.display='none'; return false;">Set a different start time</a>
           <div style="text-align:right">
           <select id="content_start_time" name="content[start_time]" style="display:none">
<?php
      echo "<option value=\"00:00\" selected>12:00am</option>\n";
      echo "<option value=\"00:30\">12:30am</option>\n";
      for ($i = 1; $i < 12; $i ++)
      {
         $tempi = str_pad($i, 2, "0", STR_PAD_LEFT);
         echo "<option value=\"{$tempi}:00\">{$i}:00am</option>\n";
         echo "<option value=\"{$tempi}:30\">{$i}:30am</option>\n";
      }
      echo "<option value=\"12:00\">12:00pm</option>\n";
      echo "<option value=\"12:30\">12:30pm</option>\n";
      for ($i = 1; $i < 12; $i ++)
      {
         $tempi = str_pad($i, 2, "0", STR_PAD_LEFT);
         $rtime = $i+12;
         echo "<option value=\"{$rtime}:00\">$i:00pm</option>\n";
         echo "<option value=\"{$rtime}:30\">$i:30pm</option>\n";
      }
      echo "<option value=\"11:59\">11:59pm</option>\n";
?>
           </select>
           </div>
         </td>
       </tr>

       <tr>
         <td><h5>End Date</h5><p>When should this piece of content expire?  This might be the date of the event you are advertising.</p></td>
         <td>
           <input type="text" id="end_date" class="end_date" name="content[end_date]" value="<?=$content->end_time?>">
           <p id="end_time_msg">Showing through the end of the day (11:59pm)</p>
         </td>
         <td width="30%" style="text-align:right;"><a href="#" onclick = "this.parentNode.getElementsByTagName('select')[0].style.display='inline'; document.getElementById('end_time_msg').style.display='none'; return false;">Set a different end time</a>
           <div style="text-align:right">
           <select id="content_end_time" name="content[end_time]" style="display:none">
<?php
      echo "<option value=\"00:00\">12:00am</option>\n";
      echo "<option value=\"00:30\">12:30am</option>\n";
      for ($i = 1; $i < 12; $i += 2)
      {
         $tempi = str_pad($i, 2, "0", STR_PAD_LEFT);
         echo "<option value=\"{$tempi}:00\">{$i}:00am</option>\n";
         echo "<option value=\"{$tempi}:30\">{$i}:30am</option>\n";
      }
      echo "<option value=\"12:00\">12:00pm</option>\n";
      echo "<option value=\"12:30\">12:30pm</option>\n";
      for ($i = 1; $i < 12; $i += 2)
      {
         $tempi = str_pad($i, 2, "0", STR_PAD_LEFT);
         $rtime = $i+12;
         echo "<option value=\"{$rtime}:00\">$i:00pm</option>\n";
         echo "<option value=\"{$rtime}:30\">$i:30pm</option>\n";
      }
      echo "<option value=\"11:59\" selected>11:59pm</option>\n";
?>
           </select>
         </td>
       </tr>

       <tr>
         <td><h5>Duration</h5><p>For how long should this piece of content be displayed on a screen?</p></td>
         <td>
           Default is <?=DEFAULT_DURATION?> seconds 
         </td>
         <td width="30%" style="text-align:right;"><a href="#" onclick = "this.parentNode.getElementsByTagName('div')[0].style.display='block'; return false;">Set a different duration</a>
           <div id="content_duration_div" style="display:none"><input type="text" size="2" id="width" name="content[duration]" id="content_duration" value="<?= $content->duration?$content->end_time:DEFAULT_DURATION?>"> &nbsp;seconds</div>
         </td>
       </tr>
       <tr>
         <td><h5>Feeds</h5><p>In which content categories would this content fit the best?  <b>Please limit to the most relevant category.</b> <a href="#"><img class="icon" src="<?= ADMIN_BASE_URL ?>images/help_button.gif" alt="Help" /></a></p></td>
         <td id="feed_cell"><div>
           Submit to Feed:
           <select name="content[feeds][0]" id="1">
           <option></option>
           <?php
           foreach ($this->feeds as $arr) {
              list($feed, $value) = $arr;
              echo '<option value="'.$feed->id.'"';
              if($checked) echo ' selected';
              echo '>'.$feed->name.'</option>';
           }
           ?>
           </select>
           </div></td>
         <td style="text-align:right;"><a href="#" onClick = "var c=this.parentNode.parentNode.getElementsByTagName('td')[1]; var n =c.lastChild.cloneNode(true); var s=n.getElementsByTagName('select')[0]; s.id=1+parseInt(s.id); s.name='content[feeds]['+s.id+']'; c.appendChild(n); return false;">Add another feed</a></td>
       </tr>       
     </table>
     </div>
	<br clear="all" />
<!-- End Screen Form General Section -->
