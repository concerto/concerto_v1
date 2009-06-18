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
class page_categoriesController extends Controller
{
   public $actionNames = Array( 'list'=> 'Category Listing', 'show'=>'Details', 'new'=>'New',
                                'edit'=> 'Edit', 'delete'=>'Delete');

   public $require = Array( 'require_login'=>1,
                            'require_action_auth'=>1 );

   function setup()
   {
      $this->setName("Page Categories");
   }

   function indexAction()
   {
      $this->listAction();
      $this->renderView("page_categories", "list");
   }

   function listAction()
   {
      $this->categories=sql_select('page_category','*');
   }

   function editAction()
   {
      list($this->category) = sql_select('page_category','*','id = '.$this->args[1]);
      if(!$this->category) {
          $this->flash('Page Category not found', 'error');
          redirect_to(ADMIN_URL."/page_categories");
      }

      $this->setSubject($this->feed->name);
      $this->setTitle("Editing ".$this->feed->name);
   }

   function showAction()
   {
      list($this->category) = sql_select('page_category',Array('page_category.*','page.name as default_page_name'),null,
                                         'LEFT JOIN page on page.id = default_page '.
                                         'WHERE page_category.id = '.$this->args[1]);
      $sql = 'SELECT COUNT(page.id) FROM page WHERE page_category_id='.$this->category['id'];
      $this->count = sql_query1($sql);
      if($this->count < 0)
         $this->count = 0;

      if(!$this->category) {
         $this->flash('Category not found', 'error');
         redirect_to(ADMIN_URL."/page_categories");
      }
      $this->setSubject($this->category['name']);
      $this->setTitle($this->category['name']);
   }
   
   function newAction()
   {
      $this->setTitle("Create a new category");
   }

   function deleteAction()
   {
      $this->showAction();
      $this->renderView('show');
      $this->setTitle('Deleting '.$this->category['name']);
      $this->flash('Do you really want to remove <strong>'.$this->category['name'].'</strong>? <br />'.
                   '<a href="'.ADMIN_URL.'/page_categories/destroy/'.$this->category['id'].'">Yes</a> | '.
                   '<a href="'.ADMIN_URL.'/page_categories/show/'.$this->category['id'].'">No</a>','warn');
   }

   function createAction()
   {
      $this->Settitle('Feed Creation');
      $dat = $_POST['category'];
      $name = escape($dat['name']);
      $path = escape($dat['path']);
      $layout = escape($dat['layout']);

      if(is_string($name) && is_string($path) && is_string($layout)) {
         $sql = "INSERT INTO `page_category` (`name`, `path`, `layout`) VALUES ('$name', '$path', '$layout');";
         $res=sql_command($sql);
      }

      if($res>0) {
         $this->flash($name.' was created successfully.');
         redirect_to(ADMIN_URL.'/page_categories');
      } else {
         $this->flash('Your category creation failed. '.$sql . mysql_error().
                      'Please check all fields and try again; contact an administrator if all else fails.','error');
         redirect_to(ADMIN_URL.'/page_categories/new');
      }
   }

   function updateAction()
   {
      $id = $this->args[1];
      $dat = $_POST['category'];
      $name = escape($dat['name']);
      $path = escape($dat['path']);
      $layout = escape($dat['layout']);
      $default_page = $dat['default_page'];

      if(is_numeric($default_page) && is_string($name) && is_string($path) && is_string($layout) && is_numeric($id)) {
         $sql = "UPDATE `page_category` SET `name`='$name', `path`='$path', `layout`='$layout', `default_page`='$default_page' ".
            "WHERE id=$id LIMIT 1";
         $res = sql_command($sql);
      }

      if($res>0) {
         $this->flash('Category updated successfully');
         redirect_to(ADMIN_URL.'/page_categories/show/'.$id);
      } else {
         $this->flash('Category update failed. Please try again.  '.$sql . mysql_error(),'error');
         redirect_to(ADMIN_URL.'/page_categories/edit/'.$id);
      }
   }

   function destroyAction()
   {
      $id = $this->args[1];
      if(is_numeric($id)) {
         $res = sql_command('DELETE FROM `page_category` WHERE `page_category`.`id` = '.escape($id));
      }

      if($res>0) {
         $this->flash('Category destroyed successfully');
         redirect_to(ADMIN_URL.'/page_categories');
      } else {
         $this->flash('There was an error removing the category.','error');
         redirect_to(ADMIN_URL.'/page_categories/show/'.$this->args[1]);
      }
   }
}
?>
