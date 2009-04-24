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
        var remove_feed = function() {
            var element = $(this).parents("li");
            var hidden = $(element).find("input:hidden");
            if($(element).siblings().length == 0)
                $(element).before('<li class="no_sub">(no current subscriptions)</li>');
            $(element).parents("div.roundcont_main").find("select.add_feed")
                .append($("<option>")
                        .attr("value", $(hidden).attr("value"))
                        .html($(hidden).attr("name")));
            $(element).remove();
            return false;
        };

        $("a.add_feed").click(function() {
            var anchor = $(this).parents("div.roundcont_main");
            var field_id = $(anchor).find("input:hidden[name=field]").attr("value");
            var feed_id = $(anchor).find("select.add_feed").val();
            if(feed_id != "") {
                var feed_desc = $(anchor).find("select.add_feed option:selected").attr("title");
                var feed_name = $(anchor).find("select.add_feed option:selected").remove().text();
                $(anchor).find("li.no_sub").remove();
                $(anchor).find("ul")
                    .append('<li><select name="content[freq][' + field_id + '][' + feed_id + ']"><option value="1">Very Seldom</option><option value="2">Occasionally</option><option value="3" selected="selected">Regularly</option><option value="4">Frequently</option><option value="5">Very Often</option></select><input type="hidden" name="System Time & Date" value="0" /> display content from <a href="/tom/admin/index.php/feeds/show/' + feed_id + '" title="'+feed_desc+'">' + feed_name + '</a> (<a class="remove_feed" href="#">remove</a>)</li>')
                    .find("a.remove_feed").click(remove_feed);
            }
            return false;
        });
        
        $("a.remove_feed").click(remove_feed);
    });
})(jQuery);
//--></script>
<img src="<?=ADMIN_URL?>/templates/preview/<?=$this->templateobj->id?>" style=" border: 1px solid #aaa; display:inline;margin:25px;float:left" alt="preview" />
<div style=" height:260px; top:0px; float:left; margin-left:30px;">
   <p style="width:280px; bottom:150px; padding:30px; margin-top:50px; background:url(../images/lightblue_bg.gif); border:1px solid #aaa">Your screen is divided up into several areas, called <b>fields</b>, each of which can display different types of content.  Use these controls to select feeds (categories of content, i.e. <i>Student Union</i>, which contains student clubs' content) to place in each field, and how often to display each.</p>
</div>
<br clear="left" />
<form method="post" action="<?=ADMIN_URL?>/screens/subscribe/<?=$this->screen->id?>">
<?php
$fields_list=$this->screen->list_fields();
if(is_array($fields_list)){
foreach($fields_list as $field) {
?>

<div class="roundcont">
  <div class="roundtop"><span class="rt"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
  <div class="roundcont_main">
    <input type="hidden" name="field" value="<?=$field->id?>" />
    <h1><span class="emph"><? echo $field->name ?></span> (Field)</h1>
    
    <ul>
<?php
$positions = $field->list_positions();
if(is_array($positions)) {
   foreach($positions as $pos) {
		$feed = new Feed($pos->feed_id);	
		$value = $pos->weight;

      echo '    <li><select name="content[freq]['.$field->id.']['.$feed->id.']">';
      echo '<option value="1"'.($value==1?' selected="selected"':'').'>Very Seldom</option>';
      echo '<option value="2"'.($value==2?' selected="selected"':'').'>Occasionally</option>';
      echo '<option value="3"'.($value==3?' selected="selected"':'').'>Regularly</option>';
      echo '<option value="4"'.($value==4?' selected="selected"':'').'>Frequently</option>';
      echo '<option value="5"'.($value==5?' selected="selected"':'').'>Very Often</option>';
      echo '</select>';
      echo '<input type="hidden" name="'.htmlspecialchars($feed->name).'" value="'.$feed->id.'" />';
?> display content from <a href="<?=ADMIN_URL.'/feeds/show/'.$feed->id?>" title="<?=htmlspecialchars($feed->description)?>"><?=htmlspecialchars($feed->name)?></a> (<a class="remove_feed" href="#">remove</a>)</li>
<?php
   }
} else echo '    <li class="no_sub">(no current subscriptions)</li>'; ?>
    </ul>
	 <p>
	  Add a feed to this field: 
	  <select class="add_feed">
          <option value="" selected="selected" title="Select a feed"></option>
     <? foreach($field->avail_feeds() as $feed) { ?>
     <option value="<?=$feed->id?>" title="<?=htmlspecialchars($feed->description)?>"><?=htmlspecialchars($feed->name)?></option>
     <? } ?>
	  </select>
     <a class="add_feed" href="#">Add</a>
	 </p>

  </div>
  <div class="roundbottom"><span class="rb"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
</div>
<?php
}
}
?>
<input type="submit" value="Submit" />
</form>
