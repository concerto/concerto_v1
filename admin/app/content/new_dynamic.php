<div style="height:220px; width:330px; float:left;">
   <img src="<?= ADMIN_BASE_URL ?>images/dynamic_text_icon.jpg" alt="" />
</div>
<h1 class="addcontent">Add Dynamic Text</h1>
<h2>Fill in these details to post a dynamic data item to Concerto.</h2>
<div style="clear:both;"></div>
<form method="post" action="<?=ADMIN_URL?>/content/create">
<br /><br />

<table class='edit_win' cellpadding='6' cellspacing='0'>
 <tr>
	 <td>
		 <h5>Submit to Dynamic Feed</h5>
		 <p><b>Select the dynamic feed to which you would like to add this text item.</b></p>
	</td>
	<td class="edit_col">
		 <div class="feeddiv">
               <select class="feedsel" name="content[feeds][0]"
                       onChange="">
<?php
foreach ($this->ndc_feeds as $arr) {
    list($feed, $value) = $arr;?>
                 <option class="feedopt"
                   title="<?=$feed->description ? $feed->description : ' '?>"
                   value="<?=$feed->id?>"><?=$feed->name?></option>

<? } ?>

               </select>
			 <br />
			 <div style="margin-top:4px;" class="feeddesc"><p> </p></div>
			 <div style="clear:both;"></div>
		 </div>
	 </td>
 </tr>
</table>
<br clear="all" />
<table class='edit_win' style="margin-top:-18px" cellpadding='6' cellspacing='0'>
<tr>
         <td><h5>Title</h5><p>Enter the title.</p></td>
         <td colspan="2" class='edit_col'>
           <input type="text" class="extended" name="content[name]" value="<?=$content->name?>" />
         </td>
   </tr>
  <tr>
  <td><h5>Body</h5><p>Enter the body.</p></td>
  <td class="edit_col">
    <input type="text" name="content[content]" id="content" class="extended" />
    <input name="content[upload_type]" value="dynamic" type="hidden" />
  </td>
  </tr>
</table>
<br />
<?php
   //assuming $this->user is null or the screen we want to edit
   $content = $this->content;
?>
<!-- Begin Content Form -->
     <table class='edit_win' cellpadding='6' cellspacing='0'>
       <tr>
         <td><h5>Start Date</h5><p>When does the event start?</p></td>
         <td class="edit_col">
           Date:
           <input type="text" class="start_date" name="content[start_date]" value="<?=$content->start_time?>" />
                Time:
           <select name="content[start_time_hr]">
           <option value="12">12</option>
<?php
      for ($i = 1; $i < 12; $i ++)
      {
         $tempi = str_pad($i, 2, "0", STR_PAD_LEFT);
         echo "<option value=\"{$tempi}\">{$i}</option>\n";
      }
     ?>
           </select> :
           <select name="content[start_time_min]">
<?php
      for ($i = 0; $i < 60; $i ++)
      {
         $tempi = str_pad($i, 2, "0", STR_PAD_LEFT);
         echo "<option value=\"{$tempi}\">{$tempi}</option>\n";
      }
     ?>
           </select>&nbsp;
           <select name="content[start_time_ampm]">
                 <option value="am">am</option>
                 <option value="pm" selected="selected">pm</option>
           </select>
         </td>
       </tr>

       <tr>
         <td><h5>End Date</h5><p>When does the event end?</p></td>
         <td>
           Date:
           <input type="text" class="end_date" name="content[end_date]" value="<?=$content->end_time?>" />
                Time:
           <select name="content[end_time_hr]">
           <option value="12">12</option>
<?php
      for ($i = 1; $i < 12; $i ++)
      {
         $tempi = str_pad($i, 2, "0", STR_PAD_LEFT);
         echo "<option value=\"{$tempi}\">{$i}</option>\n";
      }
     ?>
           </select> :
           <select name="content[end_time_min]">
<?php
      for ($i = 0; $i < 60; $i ++)
      {
         $tempi = str_pad($i, 2, "0", STR_PAD_LEFT);
         echo "<option value=\"{$tempi}\">{$tempi}</option>\n";
      }
     ?>
           </select>&nbsp;
           <select name="content[end_time_ampm]">
                 <option value="am">am</option>
                 <option value="pm" selected="selected">pm</option>
           </select>
         </td>
       </tr>
     </table>
     <br /><br />
   <br clear="all" />
<!-- End Screen Form General Section -->
<input value="Submit Content" type="submit" name="submit" />
</form>
