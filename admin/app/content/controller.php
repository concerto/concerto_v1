<?php
class contentController extends Controller
{
   public $actionNames = Array( 'list'=> 'Content Listing', 'show'=>'Details',
                                'edit'=> 'Edit', 'new'=>'Submit Content');

   public $require = Array( 'require_login'=>Array('index','list','show'),
                            'require_action_auth'=>Array('edit', 'new', 
                                                         'update', 'destroy', 'create') );

   function setup()
   {
      $this->setName('Content');
      $this->setTemplate('blank_layout', Array('image','new_image','new_ticker'));
   }

   function indexAction()
   {
      $this->listAction();
      $this->renderView("content", "list");
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
      $content = new Content($this->args[1]);
      if($content->mime_type = 'image/jpeg') {
         $this->file = IMAGE_DIR .'/'. $content->content;
            $this->height = $_GET['height'];
         $this->width = $_GET['width'];
      } else if ($content->id) {
         echo "Content type not supported";
         exit();
      }
   }

   function showAction()
   {
      $this->content = new Content($this->args[1]);
      if(!$this->content->id) {//not a real content
         $this->flash('Error: The content you requested could not be found. '.
                      'You may have found a bad link, or the content may have been removed. ',
                      'error');
         redirect_to(ADMIN_URL.'/content');
      }
      $this->setTitle($this->content->name);
      $this->canEdit =$_SESSION['user']->can_write('content',$this->args[1]);
      $this->submitter = new User($this->content->user_id);
      $feeds = $this->content->list_feeds();
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

   function editAction()
   {
      $this->content = new Content($this->args[1]);
      $this->setTitle("Editing Content: ".$this->content->name);
   }
   
   function newAction()
   {
      $this->setTitle("Add Content");
   }

   function new_imageAction()
   {
      $this->readFeeds(Feed::get_all('WHERE type=0'));
   }

   function new_tickerAction()
   {
      $this->readFeeds(Feed::get_all('WHERE type=0'));      
   }

   //just a helper to store feeds for listing in form
   function readFeeds($unsub_feeds, $sub_feeds="")
   {
      $this->feeds = Array();
      if(is_array($unsub_feeds))
         foreach ($unsub_feeds as $feed) 
            $this->feeds[$feed->name]=Array($feed,0);
      if(is_array($sub_feeds))
         foreach ($sub_feeds as $feed)
            $this->feeds[$feed->name]=Array($feed,1);

      ksort($this->feeds); //sort all feeds by feed name
   }

   function createAction()
   {
      $dat = $_POST['content'];

      if($dat['upload_type']=='file')
         $content_val = $_FILES['content_file'];
      else
         $content_val = $dat['content'];

      if(is_array($dat['feeds'])) $feed_ids=array_keys($dat['feeds']);
      else $feed_ids=Array();

      $uploader = new Uploader($dat['name'], $dat['start_time'],
                               $dat['end_time'], $feed_ids, $dat['duration']*1000, 
                               $content_val, $dat['upload_type'], $_SESSION[user]->id, 1);
                             
      if($uploader->retval) {
         $this->flash('Your content was succesfully uploaded! It will be active on the '.
                  'system as soon as it is approved for the feed(s) you chose. '.$uploader->status);
            redirect_to(ADMIN_URL.'/content/show'.$uploader->cid);
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
