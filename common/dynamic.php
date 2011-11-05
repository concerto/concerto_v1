<?
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technologies Group)
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
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */
/*
Class: Dynamic
Status: Complete rebuild
Functionality:  
        update			calls the appropriate update function for a dynamic feed
        xml_update	fetches xml and passes off to the xml_handler
        ndc_update	builds xml and passes off to the xml_handler
        xml_hander	takes xml and builts content items
        bailiwick_handler	handles substition, transforms, and general rules
        add_content	adds content items, removes old ones
        needs_update	returns the number of unprocessed items for this feed
        log_update	records an update as sucessful
        zero_pad	pads a number with some zeros
Comments:		
Completely redesigned by BAM on 10/1/2008.
All old rulesets need to be manually upgraded to new syntax.
Nearly Dynamic Content support added.
*/

class Dynamic{
  var $id;
  var $type;
  var $path;
  var $rules;
  var $update_interval;
  var $last_update;
  var $status;
  
  var $feed;
  var $content; //An array of content we create from the RSS feed
  
  var $feed_set;
  var $set;
  
  function __construct($id = '', $feed_id=''){
    $this->status = "";
    if($id != '' && is_numeric($id)){
      $sql = "SELECT *, NOW() as curtime FROM dynamic WHERE id = $id LIMIT 1";
      $res = sql_query($sql);
      if($res){
        $data = (sql_row_keyed($res,0));
        $this->id = $data['id'];
        $this->type = $data['type'];
        $this->path = stripslashes($data['path']);
        $this->rules = unserialize($data['rules']);
        $this->update_interval = $data['update_interval'];
        $this->last_update = $data['last_update'];
        $this->curtime = $data['curtime']; //Trust SQL only for the time!
        
        if($feed_id != ''){
          $this->feed = new Feed($feed_id, false);  //The false is critical here!
          $this->feed_set = true;
        } else {
          $this->feed_set = false;
        }
        
        $this->set = true;
        return true;
      } else {
        return false;
      }
    } else {
      $this->set = false;
      return true;
    }
  }
  
  function update(){
    //Determine if we want an update before we run one
    if((strtotime($this->curtime) - strtotime($this->last_update)) >= $this->update_interval){
      $return = true;
      if($this->type == 1){
        $return = $this->xml_update();
      }elseif($this->type == 2){
        $return = $this->ndc_update();
      } else {
        $this->status .= "Unknown update handler (" . $this->type . ")";
        return false;
      }
      if($return){
        $ret_val = $this->add_content();
        if($ret_val){
          $this->log_update();
          $this->status .= "Updated Completed";
          return true;
        } else {
          $this->status .= "Failure to add content. ";
          return false;
        }
      } else {
        $this->status .= "Updated Failed";
        return false;
      }
    } else {
      $this->status = "Not time for an update.  Last Update " . (strtotime($this->curtime) - strtotime($this->last_update)) . ", Threshold " . $this->update_interval;
      return true; //No update was run because we just ran one
    }
  }
  
  //Get the XML content and pass it off to the xml handler
  function xml_update(){
    if(($xml = simplexml_load_file($this->path)) && !is_bool($xml)){
      return $this->xml_handler($xml);
    } else {
      $this->status .= "Unable to open dynamic path ({$this->path}). ";
      return false;
    }
  }
  
  //Build XML from nearly dynamic content and pass it off to the xml handler
  function ndc_update(){
    if(!$this->feed_set){
      $this->status .= "No feed defined. ";
      return false;
    }
    $content_arr = $this->feed->content_get_by_type(4,'feed_content.moderation_flag = 1 ' . $this->path);
    $xml = new SimpleXMLElement('<xml></xml>');
    if(!$content_arr){
      $content_arr = array();
    }
    foreach($content_arr as $content){
      $c_xml = $xml->addChild('content');
      $c_xml->addChild('id', $content->id);
      $c_xml->addChild('title', htmlspecialchars($content->name));
      $c_xml->addChild('user_id', $content->user_id);
      $c_xml->addChild('body', htmlspecialchars($content->content));
      $c_xml->addChild('start_time', $content->start_time);
      $c_xml->addChild('end_time', $content->end_time);
      $c_xml->addChild('submitted', $content->submitted);
    }
    //echo $xml->asXML();
    return $this->xml_handler($xml);
  }
  
	//Take XML and apply rules to generate content items
  function xml_handler($xml){
    //Setup some defaults if needed
    if(is_numeric($this->rules['items_per_content'])){
      $items_per_content = $this->rules['items_per_content'];
    } else {
      $items_per_content = 3;
    }
    if(is_numeric($this->rules['max_items'])){
      $max_items = $this->rules['max_items'];
    } else {
      $max_items = 0;
    }
    
    //Process Header
    if(array_key_exists('header', $this->rules)){
      $header_arr = $this->rules['header'];
      $header_template = '';
      if(array_key_exists('template', $header_arr)){
        $header_template = $header_arr['template']; //Populate it with the default template
      }
      if(is_array($header_arr['bailiwick'])){
        $header_template = $this->bailiwick_handler($header_template, $xml, $header_arr['bailiwick']);
      }
      $header = $header_template;
    } else {
      $header = '';
    }
    //Process Glue
    if(array_key_exists('glue', $this->rules)){
      $glue_arr = $this->rules['glue'];
      $glue_template = '';
      if(array_key_exists('template', $glue_arr)){
        $glue_template = $glue_arr['template']; //Populate it with the default template
      }
      if(is_array($glue_arr['bailiwick'])){
        $glue_template = $this->bailiwick_handler($glue_template, $xml, $glue_arr['bailiwick']);
      }
      $glue = $glue_template;
    } else {
      $glue = '';
    }
    
    //Process Footer
    if(array_key_exists('footer', $this->rules)){
      $footer_arr = $this->rules['footer'];
      $footer_template = '';
      if(array_key_exists('template', $footer_arr)){
        $footer_template = $footer_arr['template']; //Populate it with the default template
      }
      if(is_array($footer_arr['bailiwick'])){
        $footer_template = $this->bailiwick_handler($footer_template, $xml, $footer_arr['bailiwick']);
      }
      $footer = $footer_template;
    } else {
      $footer = '';
    }
   
    //Process Repeat Units
    $ru = $this->rules['repeat'];
    $data = array(); //Where we will hold the content items aka repeat units
    foreach($xml->xpath($ru['xpath']) as $item){
      $content_template = ''; //Temp_Dat will hold the working string item
      if(array_key_exists('template', $ru)){
        $content_template = $ru['template']; //Populate it with the default template
      }
      if(is_array($ru['bailiwick'])){
        $content_template = $this->bailiwick_handler($content_template, $item, $ru['bailiwick']);
      }
      $data[] = $content_template;
    }
    
    //Filter out the max items if needed
    if($max_items > 0){
      $data = array_slice($data, 0, $max_items, true);
    }
    
    //Group repeat units
    $content_count = floor((count($data) + ($items_per_content - 1)) / ($items_per_content));
    $temp_content = array();
    foreach ($data as $key => $content_text){
      $cur_count = floor($key / $items_per_content);
      $temp_content[$cur_count][] = $content_text;
    }
    
    //Condesnse the content.  H.RU.G.RU.G.RU.F
    foreach($temp_content as $key => $content){
      $this->content[$key] = $header . implode($glue, $content) . $footer;
    }
    //echo "HEADER: $header <hr /> GLUE: $glue <hr /> FOOTER: $footer <hr />";
    //echo "<h1>Feed: {$this->feed->name}</h1><code>";
    //print_r($this->content);
    //print_r($data);
    //echo "</code>";
    return true;
  }
  
  //Take a string, and item, and some rules and excersize some authority
  function bailiwick_handler($string_in, $item, $baliwick_arr){
    foreach($baliwick_arr as $bailiwick){ //A bailiwick represents an individual package of transforms
      $id = $bailiwick['id'];  //This is the target we'll be operating on %%id%% in string_in
      $temp_text = '';
      if($bailiwick['type'] == 'xml'){ //Most of time it will be some XML element
        $temp = $item->xpath($bailiwick['value']);
        if(is_numeric($bailiwick['item_num'])){
          $temp_text = $temp[$bailiwick['item_num']];
        } else{
          $temp_text = $temp[0];
        }
      }elseif($bailiwick['type'] == 'xml_a'){ //Sometimes it might be an attribute
        $temp = $item->attributes();
        if(is_numeric($bailiwick['item_num'])){
          $temp_text = $temp[$bailiwick['value']][$bailiwick['item_num']];
        } else{
          $temp_text = $temp[$bailiwick['value']][0];
        }
      }elseif($bailiwick['type'] == 'xml_ns'){ //Incase a special namespace needs to be registered first
        $item->registerXPathNamespace('ns', $bailiwick['namespace']);
        $temp = $item->xpath('ns:' . $bailiwick['value']);
        if(is_numeric($bailiwick['item_num'])){
          $temp_text = $temp[$bailiwick['item_num']];
        } else{
          $temp_text = $temp[0];
        }
      
      }elseif($bailiwick['type'] == 'date'){ //Incase a special date is needed
        $temp_text = date($bailiwick['value']); 
      }elseif($bailiwick['type'] == 'static'){ //This is kind of illogical
        $temp_text = $bailiwick['value'];
      }
      //Apply any transforms if needed
      //Apply and preg_replaces if needed.
      if(count($bailiwick['transform']) > 0){
        foreach($bailiwick['transform'] as $transform){
          if($transform['type'] == 'preg'){  //Regular Expression transforms
            $reg_array = $transform['value'];
            if(is_numeric($reg_array['limit'])){
              $limit = $reg_array['limit'];
            }else{
            $limit = -1; //No limit
            }
            $temp_text = preg_replace($reg_array['pattern'], $reg_array['replacement'], $temp_text, $limit);
          }elseif($transform['type'] == 'date'){ //Date based transforms
            $format = $transform['value'];
            $temp_text = date($format, strtotime($temp_text));
          }elseif($transform['type'] == 'striptags'){ //Tag shaving
            if(array_key_exists('value', $transform)){
              $allow_tags = $transform['value'];
            }else{
              $allow_tags = '';
            }
            $temp_text = strip_tags($temp_text, $allow_tags);
          }
        }
      }
      //Truncate the block after maxchar if needed
      if(is_numeric($bailiwick['maxchar']) && $bailiwick['maxchar'] > -1){
        if(strlen($temp_text) > $bailiwick['maxchar']){
          $temp_text = substr($temp_text, 0, $bailiwick['maxchar']) . '...';
        }
      }
      //Now we actually do the replacement
      $string_in = str_replace('%%' . $id . '%%', $temp_text, $string_in);
    }
    return $string_in;
  }
  
  //THe finale, where content is added and all is well again in Yorkshire
  function add_content(){
    $name = $this->feed->name;
    
    //Begin Generic properties for all conttent. Generic is capitalized for a reason.
    $c_owner = 0;
    $mime_type = 'text/html';
    $type_id = 1;
    $duration = 14000;
    $start_time_str = 'midnight';
    $end_time_str = 'midnight +1 day';
    //End Generic properties for all content
    
    //Override things as needed
    if(is_numeric($this->rules['duration'])){
      $duration = $this->rules['duration'];
    }
    if(is_numeric($this->rules['type_id'])){
      $type_id = $this->rules['type_id'];
    }
    if(isset($this->rules['mime_type'])){
      $mime_type = $this->rules['mime_type'];
    }
    if(isset($this->rules['start_time_str'])){
      $start_time_str = $this->rules['start_time_str'];
    }
    if(isset($this->rules['end_time_str'])){
      $end_time_str = $this->rules['end_time_str'];
    }
    
    $start_time = date("Y-m-d G:i:s",strtotime($start_time_str));
    $end_time = date("Y-m-d G:i:s",strtotime($end_time_str));
    
    $existing_content = $this->feed->content_get_by_type($type_id, "content.user_id = $c_owner");
    if(is_array($existing_content)){
      $existing_count = count($existing_content);
    } else {
      $existing_count = 0;
    }
    
    $max_digits = floor(1+log($this->rules['items_per_content']*count($this->content),10));
    
    if(!defined("NOTIF_OFF")){ //Turn off notifications so we don't generate thousands
      define("NOTIF_OFF",1);
    }
    //Step 1: Create new content objects if we don't have enough
    while($existing_count < count($this->content)){
      $obj = new Content();
      if($obj->create_content("New Dynamic Content", $c_owner, "", $mime_type, $type_id, $start_time, $end_time)){
        //We can't forget to add it to that feed!
        $this->feed->content_add($obj->id, 0, 0, $duration);
        $existing_count++;
        $existing_content[] = $obj;
      } else {
        $this->status .= "Error creating needed content. ";
        echo $obj->status;
        return false; //Bomb bomb bomb.  There is a story behind that, yes
      }
    }
    //Step 2: Populate content objects with the dynamic content
    if(isset($this->content) && count($this->content) > 0){
      foreach($this->content as $key =>$item){
        $lower = $this->zero_pad($key  * $this->rules['items_per_content'] + 1, $max_digits);
        $upper = $this->zero_pad($lower + $this->rules['items_per_content'] - 1, $max_digits);
        
        if($upper != $lower){
          $c_name = $name . " ($lower-$upper)";
        } else {
          $c_name = $name . " ($lower)";
        }
        
        $return = true; //This will hold any errors we hit adding content
        
        $obj = $existing_content[$key];
        $obj->name = $c_name;
        $obj->content = $item;
        $obj->start_time = $start_time;
        $obj->end_time = $end_time;
        if($obj->set_properties()){
          $c_id = $obj->id;
          if($this->feed->content_mod($c_id, 1, 0)){
            $return = $return * true;
          } else {
            $return = $return * false;
          }
        } else {
          $return = $return * false;
        }
      }
    }else{
      $return = true;
    }
    //Step 3:  Disapprove any unused content objects
    if($return){ //Test for errors before cleaning out the old content
      for($i = count($existing_content) - 1 ; $i >= count($this->content); $i--){
        $obj = $existing_content[$i];
        //print_r($obj);
        $c_id = $obj->id;
        $this->feed->content_mod($c_id, 0, 0);  //Deny that content
        //We'll clean it out just for fun
        $obj->content = "";
        $obj->name = "Unused dynamic content";
        if($obj->set){
          $obj->set_properties();
        }
      }
      return true;
    } else {
      $this->status .= "Unknown error adding content. ";
      return false;  //Errors adding content!
    }
  }
    function preview($name = '', $user_id = '', $content = '', $start_time = '', $end_time = '', $submitted = ''){
        $xml = new SimpleXMLElement('<xml></xml>');
        $c_xml = $xml->addChild('content');
        $c_xml->addChild('id', 0);
        $c_xml->addChild('title', $name);
        $c_xml->addChild('user_id', $user_id);
        $c_xml->addChild('body', $content);
        $c_xml->addChild('start_time', $start_time);
        $c_xml->addChild('end_time', $end_time);
        $c_xml->addChild('submitted', $submitted);

        if($this->xml_handler($xml) && sizeof($this->content) > 0){
            $preview_string = "";
            foreach($this->content as $preview_content){
                $preview_string .= $preview_content;
            }
            return $preview_string;
        } else {
            return false; //A preview could not be generated
        }
    
    }
    //Returns number of content items needing processing on dynamic feed
    function needs_update(){
        $sql = "SELECT COUNT(id) as need_update FROM content LEFT JOIN feed_content ON content.id = feed_content.content_id WHERE feed_content.feed_id = {$this->feed->id} AND feed_content.moderation_flag = 1 AND content.submitted > '{$this->last_update}'";
        $res = sql_query($sql);
        if($res){
            $data = sql_row_keyed($res,0);
            if($data['need_update'] > 0){
                return $data['need_update'];
            }
        }
        return 0;
    }

  //Log the sucessful update
  function log_update(){
    $sql = "UPDATE dynamic SET last_update = NOW() WHERE id = $this->id LIMIT 1";
    sql_query($sql);
  }

  //Pad a number with some zeros
  function zero_pad($content, $desired_digits){
    if(strlen($content) < $desired_digits){
      $offset = $desired_digits - strlen($content);
      $content = str_repeat('0',$offset) . $content;
      return $content;
    }
    return $content;
  }
  
}
?>
