<?php
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
class templatesController extends Controller
{
   public $actionNames = Array( 'list'=> 'Content Listing', 'show'=>'Details',);

   public $require = Array( 'require_login'=>Array('index') );

   function setup()
   {
      $this->setName("Content");
      $this->setTemplate("blank_layout", "preview");
   }
   function indexAction()
   {
      $this->listAction();
      $this->renderView("content", "list");
   }
  
   function listAction()
   {
   }

   function previewAction()
   {
      $this->p_template = new Template($this->args[1]);
      if(!$this->p_template->set){
        return false;
      }
      if(array_key_exists(2, $this)) {
        $this->act_field = $this->args[2];
      } else {
        $this->act_field = NULL;
      }
      $this->width = '400';
      $this->height = '300';
      if(isset($_REQUEST['width'])) {
         $this->width = $_REQUEST['width'];
      }
      if(isset($_REQUEST['height'])) {
         $this->height = $_REQUEST['height'];
      }
   }
}
?>
