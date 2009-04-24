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
class pagesController extends Controller
{
   public $actionNames = Array( 'list'=> 'Page Listing', 'show'=>'Read',
                                'edit'=> 'Edit', 'delete'=>'Delete', 'new'=>'New');

   public $require = Array( 'require_action_auth'=>Array('edit','create', 'up', 'dn',
                                                         'new', 'update', 'setdefault',
                                                         'delete', 'destroy' ) );

   function setup()
   {
      $this->setName("Pages");
      $this->setTemplate('blank_layout', Array('feedback'));
   }

   function indexAction()
   {
      $this->listAction();
      $this->renderView("pages", "list");
   }

   function listAction()
   {
      $this->canEdit = isAdmin();
      $this->pages = sql_select('page',Array('page.id', 'page.name', 'page_category.path', 'page_category.name AS cat', 'in_menu','page_category_id'),null,"LEFT JOIN `page_category` ON `page_category`.`id` = `page_category_id` ".
                                "ORDER BY `page_category_id`,`order`");
   }

   function showAction()
   {
      $this->canEdit = isAdmin();

      //figure out page and category to show
      if(isset($this->args[1])) {
         list($this->category) = sql_select('page_category','*','path LIKE \''.
                                            escape($this->args[1]).'\'');
      }
      if(is_numeric($this->category['id'])) {
         if(is_numeric($this->args[2])) {
            //Prevent duplicate URLs by sending requests for category's default page
            //  to category url with no page id
            if($this->args[2]==$this->category['default_page']) {
               redirect_to(ADMIN_URL.'/pages/show/'.$this->category['path']);
            }
            list($this->page) = sql_select('page','*','id = '.escape($this->args[2]).
                                           ' AND page_category_id = '.$this->category['id']);
         } else {
            //No page specified, so we go to the category default
            list($this->page) = sql_select('page','*','page_category_id = '.$this->category['id'].
                                           ' AND id='.$this->category['default_page'],
                                           'LIMIT 1');
         }
      }
      
      //get links to show in menu
      if(isset($this->category['id'])) {
         $this->menu_links = sql_select('page',Array('CONCAT(`path`,"/",`page`.`id`) as url','page.name'),
                                        NULL,'LEFT JOIN page_category ON page_category.id = page.page_category_id '.
                                        'WHERE in_menu =1 AND page_category_id = '. $this->category['id'].
                                        ' ORDER BY `order` ASC');
      }

      if(isset($this->page['id'])) {
         $this->setTitle($this->page['name']);
         $this->setSubtitle('Last updated on '.
                            date('F j, Y',strtotime($this->page['timestamp'])));
         if(isset($this->category['layout']))
            $this->template=$this->category['layout'];
         $this->setSubject($this->getTitle());
      } else {
         if(isset($this->category['id']))
            redirect_to("../");
         else
            redirect_to(ADMIN_URL);
      }
   }
   
   function editAction()
   {
      if(is_numeric($this->args[1]))
         list($this->page)=sql_select('page','*','id='.$this->args[1]);
      if(!is_numeric($this->page['id']))
         redirect_to(ADMIN_URL.'/pages');
      $this->setSubject($this->page['name']);
      $this->setTitle("Editing ".$this->page['name']);
   }

   function newAction()
   {
      $this->setTitle("Create new page");
   }

   function setdefaultAction()
   {
      $nm = escape($this->args[1]);
      $page = escape($_GET['page']);
      if(sql_command("UPDATE page_category SET `default_page` = $page WHERE `path` LIKE \"$nm\" LIMIT 1")==1) {
         $this->flash("Default page successfully updated");
      } else {
         $this->flash("Error: default page not updated",'error');
      }
      redirect_to(ADMIN_URL.'/pages');
   }

   function deleteAction()
   {
      if(is_numeric($this->args[1])) {
         list($page) = sql_select('page',Array('page.name','page.id','page_category.name AS cat'),null,'LEFT JOIN `page_category` ON page_category.id=page_category_id '.
                                        'WHERE page.id = '.escape($this->args[1]));
         $this->listAction();
         $this->renderView('list');
         $this->setTitle('Deleting '.$page['name']);
         $this->flash("Do you really want to remove <strong>{$page['name']}</strong> from {$page['cat']}? <br />".
                      '<a href="'.ADMIN_URL.'/pages/destroy/'.$page['id'].'">Yes</a> | '.
                      '<a href="'.ADMIN_URL.'/pages/">No</a>','warn');
      }
   }

   function createAction()
   {
      $cat=$_POST['page']['category'];
      $name=escape($_POST['page']['name']);
      $content=escape($_POST['page']['content']);
      $uid = $_SESSION['user']->id;
      if($_POST['page']['in_menu']) $in_menu = 1;
      else $in_menu = 0;
      if(is_numeric($cat) && is_string($name) && is_string($content) && is_numeric($uid)) {
         list($last_item) = sql_select('page','`order`',"`page_category_id` = $cat",'ORDER BY `order` DESC LIMIT 1');
         $order=$last_item['order']+1;
         $sql =  "INSERT INTO `page` (`page_category_id` ,`name` ,`content` ,`user_id` ,`timestamp` ,`order`, `in_menu`) ".
            "VALUES ('$cat', '$name', '$content', '$uid', NOW( ), '$order', '$in_menu');";
         $res = sql_command($sql);
      }
      if($res>0) {
         $this->flash($name.' was created successfully.');
         redirect_to(ADMIN_URL.'/pages/');
      } else {
         $this->flash('Your page creation failed. '.
                      'Please check all fields and try again.','error');
         redirect_to(ADMIN_URL.'/pages/new');
      }
   }

   function updateAction()
   {
      $id = $this->args[1];
      $cat=$_POST['page']['category'];
      $name=escape($_POST['page']['name']);
      $content=escape($_POST['page']['content']);
      $uid = $_SESSION['user']->id;
      if($_POST['page']['in_menu']) $in_menu = 1;
      else $in_menu = 0;
      if($_POST['page']['feedback']) $feedback = 1;
      else $feedback = 0;
      if(is_numeric($id) && is_numeric($cat) && is_string($name) && is_string($content) && is_numeric($uid)) {
         $res = sql_command("UPDATE `page` SET `page_category_id`='$cat', `name`='$name', ".
                            "`content`='$content', `user_id`='$uid' , `timestamp`=NOW(), `in_menu`=$in_menu, ".
                            "`get_feedback`=$feedback WHERE id=$id LIMIT 1");
         $sql =("UPDATE `page` SET `page_category_id`='$cat', `name`='$name', ".
                            "`content`='$content', `user_id`='$uid' , `timestamp`=NOW(), `in_menu`=$in_menu, ".
                            "`get_feedback`=$feedback WHERE id=$id LIMIT 1");
      }
      if($res>0) {
         $this->flash($name.' was updated successfully.');
         redirect_to(ADMIN_URL.'/pages/');
      } else {
         $this->flash('Your page update failed. '.
                      'Please check all fields and try again.','error');
         redirect_to(ADMIN_URL.'/pages/edit');
      }
   }

   function feedbackAction()
   {
      if(isset($_POST['submit']) && is_numeric($_POST['page_id'])) {
         if(!preg_match('/person/i',$_POST['human'])) {
            echo "Sorry, people only, please.  Feel free to try again.";
            exit(1);
         }

         $group=new Group(ADMIN_GROUP_ID);
         $dat = $_POST['feed'];
         
         if(isset($_POST['email']))
            $email = $_POST['email'];
         else
            $email = $_SESSION['user']->email;
         
         if($_POST['helpful']) 
            $helfpul = 1;
         else 
            $helpful = 0;
         
         if(isLoggedIn()) {
            $submitter=$_SESSION['user']->name.' ('.$_SESSION['user']->username.' - '.$email.')';
         } else {
            $submitter=$email;
         }

         $page=sql_query1("SELECT CONCAT(page_category.name,' :: ',page.name) AS cat FROM `page`".
                          " LEFT JOIN `page_category` ON page_category_id=page_category.id".
                          " WHERE page.id='{$_POST['page_id']}'");
         $ip = $_SERVER['REMOTE_ADDR'];
         $msg ="New page feedback from {$submitter} [$ip]\n";
         $msg.="Page: {$page}\n";
         $msg.='Found Helpful: '.($helfpul==1?'Yes':'No')."\n";
         $msg.=''."\n";
         $msg.='Feeback: '.$_POST['message']."\n";
         if($group->send_mail('New Feedback on '.$page, $msg,$email)) {
            echo "<strong>Thanks!</strong> Your feedback will help us improve our service and support.";
            exit(1);
         }
      }
      echo "Sorry, there was an error processing your request.  You can contact support directly using the email address in this page's footer below.";
   }

   function upAction()
   {
      $id = $this->args[1];
      if(is_numeric($id)) {
         list($page) = sql_select('page',Array('id', 'page_category_id', '`order`', 'name'),
                                  "id = $id");
         $cat = $page['page_category_id'];
         $old_order = $page['order'];
         list($last_item) = sql_select('page',Array('id','`order`'),"`page_category_id` = $cat ".
                                       "AND `order` < $old_order",
                                       'ORDER BY `order` DESC LIMIT 1');
         if(is_array($last_item) && count($last_item)>1) {
            $new_order = $last_item['order'];
            $res = sql_command("UPDATE page SET `order` = $new_order WHERE id = '".
                               $id.'\' LIMIT 1');
            if($res==1) 
               $res2 = sql_command("UPDATE page SET `order` = $old_order WHERE id='".
                                   $last_item['id'].'\' LIMIT 1');
         }
      }
      
      if($res2>0) {
         $this->flash($page['name'].' was moved up successfully.');
         redirect_to(ADMIN_URL.'/pages/');
      } else {
         $this->flash('Your page move failed. '.
                      'Please try again.','error');
         redirect_to(ADMIN_URL.'/pages/edit');
      }
   }

   function dnAction()
   {
      $id = $this->args[1];
      if(is_numeric($id)) {
         list($page) = sql_select('page',Array('id', 'page_category_id', '`order`', 'name'),
                                  "id = $id");
         $cat = $page['page_category_id'];
         $old_order = $page['order'];
         list($last_item) = sql_select('page',Array('id','`order`'),"`page_category_id` = $cat ".
                                       "AND `order` > $old_order",
                                       'ORDER BY `order` ASC LIMIT 1');
         if(is_array($last_item) && count($last_item)>1) {
            $new_order = $last_item['order'];
            $res = sql_command("UPDATE page SET `order` = $new_order WHERE id = '".
                               $id.'\' LIMIT 1');
            if($res==1) 
               $res2 = sql_command("UPDATE page SET `order` = $old_order WHERE id='".
                                   $last_item['id'].'\' LIMIT 1');
         }
      }
      
      if($res2>0) {
         $this->flash($page['name'].' was moved down successfully.');
         redirect_to(ADMIN_URL.'/pages/');
      } else {
         $this->flash('Your page move failed. '.
                      'Please try again.','error');
         redirect_to(ADMIN_URL.'/pages/edit');
      }
   }

   function destroyAction()
   {
      $id = $this->args[1];
      if(is_numeric($id))
         $res = sql_command('DELETE FROM `page` WHERE `page`.`id` = '.escape($id));
      if($res) {
         $this->flash('Page destroyed successfully');
         redirect_to(ADMIN_URL.'/pages');
      } else {
         $this->flash('There was an error removing the page.','error');
         redirect_to(ADMIN_URL.'/pages');
      }
   }
}
