<?php
class feedsController extends Controller
{
   public $actionNames = Array( 'list'=> 'Feeds Listing', 'show'=>'Details',
                                'edit'=> 'Edit');

   public $require = Array( 'require_login'=>1,
                            'require_action_auth'=>Array('edit','create',
                                                         'new', 'update' ) );

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
      $this->setTitle($this->feed->name);
      $this->canEdit = $_SESSION['user']->can_write('feed',$this->args[1]);
   }

   function editAction()
   {
      $this->feed = new Screen($this->args[1]);
      $this->setTitle("Editing ".$this->feed->name);
   }

   function newAction()
   {
      $this->setTitle("Create new feed");
   }

   function createAction()
   {
   }
   //feeds up to here
   function updateAction()
   {
     $screen = new Feed($this->args[1]);
     $dat = $_POST['feed'];
     $screen->name = $dat['name'];
     $screen->group_id = $dat['group'];
     $screen->location = $dat['location'];
     $screen->mac_address = $dat['mac_address'];
     $screen->width = $dat['width'];
     $screen->height = $dat['height'];
     $screen->template_id = $dat['template'];

     if($screen->set_properties()) {
        $_SESSION['flash'][]=Array('info', 'Screen Updated Successfully');
        redirect_to(ADMIN_URL.'/screens/show/'.$screen->mac_address);
     } else {
        $_SESSION['flash'][]=Array('error', 'Your submission was not valid. Please try again.');
        redirect_to(ADMIN_URL.'/screens/show/'.$this->args[1]);
     }
     print_r($screen);
   }

   function destroyAction()
   {
   }   
}
?>
