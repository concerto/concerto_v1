<?php
class feedsController extends Controller
{
   public $actionNames = Array( 'list'=> 'Feeds Listing', 'show'=>'Details',
                                'edit'=> 'Edit');

   public $require = Array( 'require_login'=>1,
                            'require_action_auth'=>Array('edit','create',
                                                         'new', 'update',
                                                         'destroy' ) );

   function setup()
   {
      $this->setName("Feeds");
   }

   function indexAction()
   {
      $this->listAction();
      $this->renderView("feeds", "list");
   }

   function listAction()
   {
      $feedids = sql_select("feed","id");
      $this->feeds=Array();
      if(is_array($feedids))
         foreach($feedids as $feed)
            $this->feeds[] = new Feed($feed['id']); 
   }

   function showAction()
   {
      $this->feed = new Feed($this->args[1]);
      $this->group = new Group($this->feed->group_id);
      $this->setTitle($this->feed->name);
      $this->canEdit = $_SESSION['user']->can_write('feed',$this->args[1]);
   }

   function editAction()
   {
      $this->feed = new Feed($this->args[1]);
      $this->setTitle("Editing ".$this->feed->name);
   }

   function newAction()
   {
      $this->setTitle("Create new feed");
   }

   function createAction()
   {
      $this->Settitle('Feed Creation');
      $feed=new Feed();

      if($feed->create_feed($_POST[feed][name],$_POST[feed][group])) {
         $this->flash($feed->name.' was created successfully.');
         redirect_to(ADMIN_URL.'/feeds/show/'.$feed->id);
      } else {
         $this->flash('Your feed creation failed. '.
                      'Please check all fields and try again; contact an administrator if all else fails.','error');
         redirect_to(ADMIN_URL.'/feeds/new');
      }
   }
   //feeds up to here
   function updateAction()
   {
      $feed = new Feed($this->args[1]);
      $dat = $_POST['feed'];
      $feed->name = $dat['name'];
      $feed->group_id = $dat['group'];

      if($feed->set_properties()) {
         $_SESSION['flash'][]=Array('info', 'Feed Updated Successfully');
         redirect_to(ADMIN_URL.'/feeds/show/'.$feed->id);
      } else {
         $_SESSION['flash'][]=Array('error', 'Your submission was not valid. Please try again.');
         redirect_to(ADMIN_URL.'/feeds/show/'.$this->args[1]);
      }
   }

   function destroyAction()
   {
      $feed = new Feed($this->args[1]);
      if($feed->destroy()) {
         $this->flash('Feed destroyed successfuly');
         redirect_to(ADMIN_URL.'/feeds');
      } else {
         $this->flash('There was an error removing the feed.','error');
         redirect_to(ADMIN_URL.'/feeds/show/'.$this->args[1]);
      }
   }
}
?>
