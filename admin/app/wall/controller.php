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
 * @author       Web Technologies Group, $Author: zaik $
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision: 543 $
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
    }
    
    function extAction() 
    {
      if(isset($_REQUEST['ajax'])){
        $this->template="blank_layout.php"; //Found this nifty hack in the moderation controller
      }
      $this->feed = new Feed($this->args[1]);
      $this->content = new Content($this->args[2]);
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
      }
      //Permissions are currently handled in the view to account for both ajax and non-ajax queries.
    }

}
?>
