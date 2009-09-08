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
class contentController extends Controller
{
   public $actionNames = Array( 'list'=> 'Content Listing', 'show'=>'Details',
                                'edit'=> 'Edit', 'new'=>'Submit Content', 'remove'=>'Delete');

   public $require = Array( 'require_login'=>Array('index','list','show','new','create','new_ticker','new_image', 'new_dynamic', 'new_preview'),
                            'require_action_auth'=>Array('edit', 'update', 'destroy', 'remove') );

   function setup()
   {
      $this->setName('Content');
      $this->setTemplate('blank_layout', Array('image','new_image','new_ticker','new_dynamic','ajax_details', 'new_preview'));
   }

   function indexAction()
   {
      redirect_to(ADMIN_URL.'/browse/');
   }
   
   function listAction()
   {
      $types = sql_select('type',Array('id','name'), NULL, 'ORDER BY name');
      foreach($types as $type) {
         $contentids = sql_select('feed_content','DISTINCT content_id', '', 'INNER JOIN `content`'.
		' ON content_id=content.id AND moderation_flag=1 AND type_id = '.$type['id'].
		' AND (content.start_time < NOW() OR content.start_time IS NULL)'.
		' AND (content.end_time > NOW() OR content.end_time IS NULL) ORDER BY name');
         if(is_array($contentids))
            foreach($contentids as $id)
               $this->contents[$type['name']][] = new Content($id['content_id']);
      }
    }

   function imageAction()
   {
      $c_id = explode('.', $this->args[1]); //In case a file extension is passed, we'll want to remove it
      $content = new Content($c_id[0]);
      $this->file = $content->content;
      $this->height = $_GET['height'];
      $this->width = $_GET['width'];
   }

   function showAction()
   {
      $this->content = new Content($this->args[1]);
      if(!is_numeric($this->content->id)) {//not a real content
         $this->flash('Error: The content you requested could not be found. '.
                      'You may have found a bad link, or the content may have been removed. ',
                      'error');
         redirect_to(ADMIN_URL.'/content');
      }
      //Decode the content name because it will be re-encoded downstream.
      $this->setTitle(htmlspecialchars_decode($this->content->name));
      $this->setSubject(htmlspecialchars_decode($this->content->name));

      $this->canEdit =$_SESSION['user']->can_write('content',$this->args[1]);
      $this->submitter = new User($this->content->user_id);
      $feeds = $this->content->list_feeds();
      $this->stats = $this->content->stats_byscreen("display");
      
      if(is_array($feeds)) {
         foreach ($feeds as $feed) {
            if($feed['moderation_flag']==1)
               $this->act_feeds[]=$feed;
            else if ($feed['moderation_flag']=='')
               $this->wait_feeds[]=$feed;
            else if ($feed['moderation_flag']==0)
               $this->denied_feeds[]=$feed;
         }
      }
   }

   function ajax_detailsAction()
   {
   }

   function editAction()
   {
      $this->content = new Content($this->args[1]);
      //Decode the content name because it will be re-encoded downstream.
      $this->setTitle("Editing Content: ".htmlspecialchars_decode($this->content->name));
      $this->setSubject(htmlspecialchars_decode($this->content->name));
   }
   
   function newAction()
   {
      $this->readFeeds(&$this->feeds, Feed::priv_get($_SESSION['user'], 'content'));
      $this->readFeeds(&$this->ndc_feeds, Feed::priv_get($_SESSION['user'], 'dynamic'));
      $this->setTitle("Add Content");
   }
   function new_imageAction()
   {
      $this->readFeeds(&$this->feeds, Feed::priv_get($_SESSION['user'], 'content'));
   }

   function new_tickerAction()
   {
      $this->readFeeds(&$this->feeds,Feed::priv_get($_SESSION['user'], 'content'));      
   }

   function new_dynamicAction()
   {
      $this->setTitle("Add Dynamic Content");
      $this->readFeeds(&$this->ndc_feeds,Feed::priv_get($_SESSION['user'], 'dynamic'));      
   }
   function new_previewAction(){
   
   }
   //just a helper to store feeds for listing in form
   function readFeeds($dest, $unsub_feeds, $sub_feeds="")
   {
      $dest = Array();
      if(is_array($unsub_feeds))
         foreach ($unsub_feeds as $feed) 
            $dest[$feed->name]=Array($feed,0);
      if(is_array($sub_feeds))
         foreach ($sub_feeds as $feed)
            $dest[$feed->name]=Array($feed,1);

      ksort($dest); //sort all feeds by feed name
   }

   function createAction()
   {
      $dat = $_POST['content'];
      if($_POST['submit']!='Add another feed') {

         if($dat['upload_type']=='file')
            $content_val = $_FILES['content_file'];
         else
            $content_val = $dat['content'];
         
         if(is_array($dat['feeds'])) $feed_ids=array_unique(array_values($dat['feeds']));
         else $feed_ids=Array();

         if($dat['upload_type'] == 'dynamic'){
             $start=$dat['start_date'].' '.$dat['start_time_hr'].':'.$dat['start_time_min'].' '.$dat['start_time_ampm'];
             $end=$dat['end_date'].' '.$dat['end_time_hr'].':'.$dat['end_time_min'].' '.$dat['end_time_ampm'];
             $dat['duration'] = strtotime($end) - strtotime($start);
         } else {
             $start=$dat['start_date'].' '.$dat['start_time'];
             $end=$dat['end_date'].' '.$dat['end_time'];
         }
         $uploader = new Uploader($dat['name'], $start,
                                  $end, $feed_ids, $dat['duration']*1000, 
                                  $content_val, $dat['upload_type'], $_SESSION[user]->id, 1);
      }
      if($uploader->retval) {
          if($dat['upload_type'] == 'dynamic') {
              $this->flash('Your content was successfully added. It will be automatically processed and will appear on the live feed shortly. '.$uploader->status);
          } else {
              $content = new Content($uploader->cid);
              $feeds = $content->list_feeds();
              $approved=0;
              $waiting=0;
              foreach ($feeds as $feed) {
                  if($feed['moderation_flag']==1){
                      $approved ++;
                  } elseif($feed['moderation_flag']==NULL) {
                      $waiting ++;
                  }
              }
              if($approved>0) {
                  $this->flash('Your content was successfuly added. It has been automatically approved on feeds for which you are a moderator. '.$uploader->status);
              } else {
                  $this->flash('Your content was succesfully uploaded! It will be active on the '.
                               'system as soon as it is approved for the feed(s) you chose. '.$uploader->status);
              }
          }
          redirect_to(ADMIN_URL.'/content/show/'.$uploader->cid);
      } else {
         $this->flash('Your content submission failed. '.
                      'Please check all fields and try again. '.$uploader->status, 'error');
         redirect_to(ADMIN_URL.'/content/new');
      }
   }

   function updateAction()
   {
      $Content = new Content($this->args[1]);
      $dat = $_POST['content'];
/*
      $user->name = $dat['name'];
      $user->email = $dat['email'];
      
      if($user->set_properties()) {
         $this->flash('User profile updated successfully.');
         redirect_to(ADMIN_URL.'/users/show/'.$user->username);
      } else {
         $this->flash('Your submission failed. Please check all fields and try again.','error');
         redirect_to(ADMIN_URL.'/users/show/'.$this->args[1]);
      }*/
   }

   function removeAction()
   {
      $this->showAction();
      $this->renderView('show');
      $this->flash("Do you really want to remove <strong>{$this->content->name}</strong>? <br />".
                   '<a href="'.ADMIN_URL.'/content/destroy/'.$this->content->id.'">Yes</a> | '.
                   '<a href="'.ADMIN_URL.'/content/show/'.$this->content->id.'">No</a>','warn');
   }

   function destroyAction()
   {
      $content=new Content($this->args[1]);
      if($content->destroy()){
         $this->flash('Content removed successfully.');
         redirect_to(ADMIN_URL.'/content');
      } else {
         $this->flash('There was an error deleting the content.');
         redirect_to(ADMIN_URL.'/content/show/'.$content->id);
      }

   }   
}
?>
