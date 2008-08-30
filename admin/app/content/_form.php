<?php
   //assuming $this->user is null or the screen we want to edit
   $content = $this->content;
?>
<!-- Begin Content Form -->
     <table class='edit_win' cellpadding='6' cellspacing='0'>
       <tr>
         <td><h5>Title</h5><p>Enter some words that describe this piece of content to others.</p></td>
         <td colspan="2" class='edit_col'>
           <input type="text" class="extended" name="content[name]" value="<?=$content->name?>" />
         </td>
       </tr>
       <tr>
         <td><h5>Start Date</h5><p>When should this piece of content start to be displayed on Concerto?</p></td>
         <td>
           <input type="text" class="start_date" name="content[start_date]" value="<?=$content->start_time?>" />
           <p class="start_time_msg">Starting at the beginning of the day (12:00am)</p>
         </td>
         <td width="30%" style="text-align:right;"><a class="click_start_time" href="#">Set a different start time</a>
           <div class="start_time_select" style="text-align:right;display:none">Start Time:
           <select name="content[start_time]">
<?php
      echo "<option value=\"00:00\" selected=\"selected\">12:00am</option>\n";
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
      echo "<option value=\"23:59\">11:59pm</option>\n";
?>
           </select>
           </div>
         </td>
       </tr>

       <tr>
         <td><h5>End Date</h5><p>When should this piece of content expire?  This might be the date of the event you are advertising.</p></td>
         <td>
           <input type="text" class="end_date" name="content[end_date]" value="<?=$content->end_time?>" />
           <p class="end_time_msg">Showing through the end of the day (11:59pm)</p>
         </td>
         <td width="30%" style="text-align:right;"><a class="click_end_time" href="#">Set a different end time</a>
           <div class="end_time_select" style="text-align:right;display:none">End Time:
           <select name="content[end_time]">
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
      echo "<option value=\"23:59\" selected=\"selected\">11:59pm</option>\n";
?>
           </select>
           </div>
         </td>
       </tr>

       <tr>
         <td><h5>Duration</h5><p>For how long should this piece of content be displayed on a screen?</p></td>
         <td>
           <div class="duration_msg">Default is <?=DEFAULT_DURATION?> seconds</div>
           <div class="duration_div" style="display:none"><input type="text" size="2" name="content[duration]" value="<?= $content->duration?$content->end_time:DEFAULT_DURATION?>" /> &nbsp;seconds</div>
         </td>
         <td width="30%" style="text-align:right;"><a class="click_duration" href="#">Set a different duration</a>
         </td>
       </tr>
     </table>
     <br /><br />
     <h2>Not sure what feeds are?  <a TARGET="_blank" href="http://signage.rpi.edu/admin/pages/show/docs/23">Read this first!</a></h2>
     <table class='edit_win' cellpadding='6' cellspacing='0'> 
       <tr>
         <td width="30%"><h5>Feed(s)</h5><p>In which content categories would this content fit the best?  <b>Please limit to the most relevant category.</b> </p></td>
         <td>
           Submit to Feed:
           <select name="content[feeds][0]">
           <option></option>
<?php
foreach ($this->feeds as $arr) {
    list($feed, $value) = $arr;?>
           <option value="<?=$feed->id?>"><?=$feed->name?></option>
<? } ?>
           </select>
         </td>
         <td style="text-align:right;"><a class="click_add_feed" href="#">Add another feed</a></td>
       </tr>
     </table>
   <br clear="all" />
<!-- End Screen Form General Section -->
