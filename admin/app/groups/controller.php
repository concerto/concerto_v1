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
class groupsController extends Controller
{
   public $actionNames = Array( 'list'=> 'Group Listing', 'show'=>'Details', 'new'=>'New',
                                'delete'=> 'Delete', 'add'=>'Add User', 'remove'=>'Remove User');

   public $require = Array( 'require_login'=>1,
                            'require_action_auth'=>Array('edit','create', 'destroy',// 'show', 'index', 'list',
                                                         'new', 'update', 'add', 'delete',
                                                         'remove', 'subscribe', 'unsubscribe') );

   function setup() 
   {
      $this->setName('Groups');
   }

   function indexAction()
   {
      $this->listAction();
      $this->renderView('list');
   }

   function listAction()
   {
      $ids = sql_select('group','id');
      $this->groups=Array();
      if(is_array($ids))
         foreach($ids as $group) { 
            $group = new Group($group['id']);
            $this->groups[$group->id][name]=$group->name;
            $members = $group->get_members();
            $this->groups[$group->id][members]=is_array($members)?count($members):0;
            $feeds_res = sql_query('SELECT COUNT(id) as feeds FROM `feed` WHERE `group_id` = '.$group->id);
            if($feeds_res) {
               $num_feeds = sql_row_keyed($feeds_res,0);
               $num_feeds = $num_feeds['feeds'];
               if($num_feeds==1)
                  $this->groups[$group->id][controls][]="1 feed";
               else if($num_feeds>1)
                  $this->groups[$group->id][controls][]=$num_feeds." feeds";
            }
            $screens_res = sql_query('SELECT COUNT(id) as screens FROM `screen` WHERE `group_id` = '.$group->id);
            if($screens_res) {
            $num_screens = sql_row_keyed($screens_res,0);
               $num_screens = $num_screens['screens'];
               if($num_screens==1)
                  $this->groups[$group->id][controls][]="1 screen";
               else if($num_screens>1)
                  $this->groups[$group->id][controls][]=$num_screens." screens";
            }
      }
   }

   function showAction()
   {
      $this->group = new Group($this->args[1]);
      $this->feeds = Feed::get_all('WHERE group_id='.$this->group->id);
      $this->screens = Screen::get_all('WHERE group_id='.$this->group->id);
      $this->setTitle($this->group->name);
      $this->setSubject($this->group->name);
      $this->canEdit =$_SESSION['user']->can_write('group',$this->args[1]);
   }

   function editAction()
   {  
      $this->setTitle('Editing '.$this->group->name);
      $this->setSubject($this->group->name);
   }

   function newAction()
   {
      $this->setTitle("Create new group");
   }

   function deleteAction()
   {
      $this->showAction();
      $this->renderView('show');
      $this->setTitle('Deleting '.$this->group->name);
      $this->flash("Do you really want to remove <strong>{$this->group->name}</strong>? <br />".
                   '<a href="'.ADMIN_URL.'/groups/destroy/'.$this->group->id.'">Yes</a> | '.
                   '<a href="'.ADMIN_URL.'/groups/show/'.$this->group->id.'">No</a>','warn');
   }

   function addAction()
   {
      $this->group = new Group($this->args[1]);
      $this->users = sql_select('user',array('username','name'),false,'LEFT JOIN user_group on '.
                                'user.id = user_group.user_id AND group_id='.$this->group->id.
                                ' WHERE group_id IS NULL ORDER BY `user`.`username` ASC ');
      $this->setTitle('Add user to '.$this->group->name);
      $this->setSubject($this->group->name);
   }

   function removeAction()
   {
      $this->group = new Group($this->args[1]);
      $this->users = $this->group->get_members();
      $this->setTitle('Remove user from '.$this->group->name);
      $this->setSubject($this->group->name);
   }

   function createAction()
   {
	$this->setTitle('Group Creation');
	$group=new Group();
         if($group->create_group($_POST[group][name])) {
            $this->flash($group->name.' was created successfully.');
            redirect_to(ADMIN_URL.'/groups/show/'.$group->id);
         } else {
            $this->flash('Your group creation failed. '.
                         'Please check all fields and try again; contact an administrator if all else fails.','error');
            redirect_to(ADMIN_URL.'/groups/new');
         }
   }

   function destroyAction()
   {
      $group=new Group($this->args[1]);
      if($group->destroy()){
         $this->flash('Group removed successfully.');
         redirect_to(ADMIN_URL.'/groups');
      } else {
         $this->flash('There was an error deleting the group.');
         redirect_to(ADMIN_URL.'/groups/show/'.$group->id);
      }
   }   

   function subscribeAction()
   {
      $user = new User($_REQUEST['user']);
      $group=new Group($this->args[1]);
      if($user->add_to_group($group->id)) {
         $this->flash($user->name.' was successfuly added to '.$group->name);
         redirect_to(ADMIN_URL.'/groups/show/'.$group->id);
      } else {
         $this->flash('There was an error adding the user.  Please try again or contact an administrator.');
         redirect_to(ADMIN_URL.'/groups/show/'.$group->id);
      }
   }
   
   function unsubscribeAction()
   {
      $user = new User($_REQUEST['user']);
      $group=new Group($this->args[1]);
      if($user->remove_from_group($group->id)) {
         $this->flash($user->name.' was successfuly removed from '.$group->name);
         redirect_to(ADMIN_URL.'/groups/show/'.$group->id);
      } else {
         $this->flash('There was an error removing the user.  Please try again or contact an administrator.');
         redirect_to(ADMIN_URL.'/groups/show/'.$group->id);
      }
   }
}
?>
