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
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */
class  wallController extends Controller
{
    public $actionNames = Array('index'=>'Concerto Wall', 'feedgrid' => 'Browse a Feed', 'ext' => 'View Content');

    function setup()
    {
        $this->setName("Concerto Wall");
        $this->setTemplate('stripped_ds_layout');
    }
    
    function indexAction()
    {
      //Find feeds with active, approved graphical content
      $this->feeds = Feed::list_all_by_type('WHERE feed.type != 3 AND type.id = 3 
                                             AND feed_content.moderation_flag = 1
                                             AND content.start_time <= NOW() AND content.end_time >= NOW() AND content.mime_type LIKE "%image%"');
      $this->content_count = array();
      if(!is_array($this->feeds)){
        $this->feeds = array();
      }
      foreach($this->feeds as $id => $feed){
          $sql = "SELECT COUNT(content.id) FROM feed_content
                LEFT JOIN content ON feed_content.content_id = content.id
                WHERE feed_content.feed_id = {$id} AND feed_content.moderation_flag = 1
                AND content.start_time <= NOW() AND content.end_time >= NOW() AND content.mime_type LIKE '%image%'
                GROUP BY feed_content.feed_id";
          $this->feeds[$id]['count'] = sql_query1($sql);
      }
    }
    
    function extAction() 
    {
      if(isset($_REQUEST['ajax'])){
        $this->template="blank_layout.php"; //Found this nifty hack in the moderation controller
      }
      $this->feed = new Feed($this->args[1]);
      $this->content = new Content($this->args[2]);
      $this->submitter = new User($this->content->user_id);
      $this->week_range = date('W',strtotime($this->content->end_time)) - date('W',strtotime($this->content->start_time));
      $this->setTitle($this->content->name);
      //Permissions are currently handled in the view to account for both ajax and non-ajax queries.
    }
    
    function feedgridAction()
    {
      if(isset($_REQUEST['ajax'])){
        $this->template="blank_layout.php";
      }
      $this->feed = new Feed($this->args[1]);
      if((strlen($this->feed->name) > 0) && $this->feed->type != 3){
        $this->contents = $this->feed->content_get_by_type(3,'feed_content.moderation_flag = 1 AND content.start_time <= NOW() AND content.end_time>= NOW() AND content.mime_type LIKE "%image%"');
        $this->setTitle($this->feed->name);
      }
      //Permissions are currently handled in the view to account for both ajax and non-ajax queries.
    }

}
?>
