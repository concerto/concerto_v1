<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technolgies Group)
 *
 * This program is free software; you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.  You should have received a copy
 * of the GNU General Public License along with this program.
 *
 * @package      Concerto
 * @author       Web Technologies Group, $Author: mike $
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision: 551 $
 */
?><script type="text/javascript"><!--
(function($) {
    $(document).ready(function() {
        var preview = function(form) {
            var loading = $("<div>").html("<h5>Loading.  Please wait...</h5>")
                .dialog({ autoResize: true,
                          draggable: false,
                          height: "auto",
                          modal: true,
                          overlay: { opacity: 0.5, background: "black" },
                          resizable: false,
                          title: "Loading..."
                        });
            $.ajax({type: "GET",
                    url: "<?=ADMIN_URL?>/content/new_preview/",
                    data: {"feed_id": $("#dd_feed", form).val(),
                           "name": $("#dd_name", form).val(),
                           "content": $("#dd_content", form).val(),
                           "start_date": $("#dd_start_date", form).val(),
                           "start_time_hr": $("#dd_start_time_hr", form).val(),
                           "start_time_min": $("#dd_start_time_min", form).val(),
                           "start_time_ampm": $("#dd_start_time_ampm", form).val(),
                           "end_date": $("#dd_end_date", form).val(),
                           "end_time_hr": $("#dd_end_time_hr", form).val(),
                           "end_time_min": $("#dd_end_time_min", form).val(),
                           "end_time_ampm": $("#dd_end_time_ampm", form).val()},
                    success: function(html){
                        $(html)
                            .dialog({
                                autoResize: true,
                                buttons: {
                                    "Cancel": function(){ $(this).dialog("destroy"); }
                                },
                                draggable: false,
                                height: "auto",
                                modal: true,
                                overlay: { opacity: 0.5, background: "black" },
                                resizable: false,
                                title: "Preview Content"
                            });
                        $(loading).dialog("destroy");
                    },
                      dataType: "html"
            });        
        }

        $("input[@name='preview']").click(function(e) {
            e.preventDefault();
            var form = $('#new_dynamic');
            preview(form);
            return false;
        });
    });
})(jQuery);
//--></script>
<div style="height:220px; width:330px; float:left;">
   <img src="<?= ADMIN_BASE_URL ?>images/dynamic_text_icon.jpg" alt="" />
</div>
<h1 class="addcontent">Add Dynamic Text</h1>
<h2>Fill in these details to post a dynamic data item to Concerto.</h2>
<div style="clear:both;"></div>
<form method="post" action="<?=ADMIN_URL?>/content/create" id="new_dynamic">
<br /><br />

<table class='edit_win' cellpadding='6' cellspacing='0'>
 <tr>
	 <td>
		 <h5>Submit to Dynamic Feed</h5>
		 <p><b>Select the dynamic feed to which you would like to add this text item.</b></p>
	</td>
	<td class="edit_col">
		 <div class="feeddiv">
               <select class="feedsel" name="content[feeds][0]" id="dd_feed">
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
           <input type="text" class="extended" id="dd_name" name="content[name]" value="<?=$content->name?> " />
         </td>
   </tr>
  <tr>
  <td><h5>Body</h5><p>Enter the body.</p></td>
  <td class="edit_col">
    <input id="dd_content" type="text" name="content[content]" id="content" class="extended" />
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
           <input type="text" class="start_date" id="dd_start_date" name="content[start_date]" value="<?=$content->start_time?>" />
                Time:
           <select id="dd_start_time_hr" name="content[start_time_hr]">
           <option value="12">12</option>
<?php
      for ($i = 1; $i < 12; $i ++)
      {
         $tempi = str_pad($i, 2, "0", STR_PAD_LEFT);
         echo "<option value=\"{$tempi}\">{$i}</option>\n";
      }
     ?>
           </select> :
           <select id="dd_start_time_min" name="content[start_time_min]">
<?php
      for ($i = 0; $i < 60; $i ++)
      {
         $tempi = str_pad($i, 2, "0", STR_PAD_LEFT);
         echo "<option value=\"{$tempi}\">{$tempi}</option>\n";
      }
     ?>
           </select>&nbsp;
           <select id="dd_start_time_ampm" name="content[start_time_ampm]">
                 <option value="am">am</option>
                 <option value="pm" selected="selected">pm</option>
           </select>
         </td>
       </tr>

       <tr>
         <td><h5>End Date</h5><p>When does the event end?</p></td>
         <td>
           Date:
           <input id="dd_end_date" type="text" class="end_date" name="content[end_date]" value="<?=$content->end_time?>" />
                Time:
           <select id="dd_end_time_hr" name="content[end_time_hr]">
           <option value="12">12</option>
<?php
      for ($i = 1; $i < 12; $i ++)
      {
         $tempi = str_pad($i, 2, "0", STR_PAD_LEFT);
         echo "<option value=\"{$tempi}\">{$i}</option>\n";
      }
     ?>
           </select> :
           <select id="dd_end_time_min" name="content[end_time_min]">
<?php
      for ($i = 0; $i < 60; $i ++)
      {
         $tempi = str_pad($i, 2, "0", STR_PAD_LEFT);
         echo "<option value=\"{$tempi}\">{$tempi}</option>\n";
      }
     ?>
           </select>&nbsp;
           <select id="dd_end_time_ampm" name="content[end_time_ampm]">
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

<input value="Preview" type="button" name="preview" />

</form>
