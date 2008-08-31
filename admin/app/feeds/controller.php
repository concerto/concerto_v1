<?php
class feedsController extends Controller
{
   public $actionNames = Array( 'list'=> 'Feeds Listing', 'show'=>'Details', 'new'=>'New',
                                'edit'=> 'Edit', 'moderate'=>'Moderate', 'delete'=>'Delete', 'request'=>'Feed Request');

   public $require = Array( 'require_login'=>1,
                            'require_action_auth'=>Array('edit','create',
                                                         'new', 'update', 'deny',
                                                         'delete', 'destroy',
                                                         'moderate', 'approve' ) );

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
      $this->feeds=Feed::priv_get($_SESSION['user'], 'list');
   }

   function editAction()
   {
      $this->feed = new Feed($this->args[1]);
      if(!$this->feed) {
          $this->flash('Feed not found', 'error');
          redirect_to(ADMIN_URL."/feeds");
      }

      $this->setSubject($this->feed->name);
      $this->setTitle("Editing ".$this->feed->name);
   }

    function showAction()
    {
      $this->feed = new Feed($this->args[1]);
        if(!$this->feed) {
            $this->flash('Feed not found', 'error');
            redirect_to(ADMIN_URL."/feeds");
        }
        
        $this->group = new Group($this->feed->group_id);

        $sql = "SELECT COUNT(content.id) FROM feed_content
                LEFT JOIN content ON feed_content.content_id = content.id
                WHERE feed_content.feed_id = {$this->feed->id}
                AND moderation_flag = 1
                AND content.end_time > NOW()
                GROUP BY feed_content.feed_id;";
        $this->active_content = sql_query1($sql);
        if($this->active_content < 0)
           $this->active_content = 0;
        
        $sql = "SELECT COUNT(content.id) FROM feed_content
                LEFT JOIN content ON feed_content.content_id = content.id
                WHERE feed_content.feed_id = {$this->feed->id}
                AND moderation_flag = 1
                AND content.end_time < NOW()
                GROUP BY feed_content.feed_id;";
        $this->expired_content = sql_query1($sql);
        if($this->expired_content < 0)
           $this->expired_content = 0;

        $this->setSubject($this->feed->name);
        $this->setTitle($this->feed->name);
    }

   function newAction()
   {
      $this->setTitle("Create new feed");
   }

   function deleteAction()
   {
      $this->showAction();
      $this->renderView('show');
      $this->setTitle('Deleting '.$this->feed->name);
      $this->flash("Do you really want to remove <strong>{$this->feed->name}</strong>? <br />".
                   '<a href="'.ADMIN_URL.'/feeds/destroy/'.$this->feed->id.'">Yes</a> | '.
                   '<a href="'.ADMIN_URL.'/feeds/show/'.$this->feed->id.'">No</a>','warn');
   }

   function requestAction()
   {
      if(isset($_POST['submit'])) {
         $group=new Group(ADMIN_GROUP_ID);
         $dat = $_POST['feed'];
         $nm = escape($_SESSION['user']->name);
         $id = $_SESSION['user']->id;
         $email = escape($_SESSION['user']->email);
         $msg ="There has been a new feed request from {$nm} - {$email} (".ADMIN_URL."/users/show/{$id})\n";
         $msg.='Name: '.escape($dat['name'])."\n";
         $msg.='Organization: '.escape($dat['org'])."\n";
         $msg.='Description: '.escape($dat['desc'])."\n";

         $group->send_mail('New Concerto Feed Request: '.escape($dat['name']), $msg,escape($_SESSION['user']->email));

         $this->flash("Your request is being processed. We'll be contacting you about the feed soon!");
         redirect_to(ADMIN_URL.'/feeds/');
      }
   }

   function createAction()
   {
      $this->Settitle('Feed Creation');
      $feed=new Feed();

      if($feed->create_feed($_POST[feed][name],$_POST[feed][group],0,$_POST[feed][description])) {
         $this->flash($feed->name.' was created successfully.');
         redirect_to(ADMIN_URL.'/feeds/show/'.$feed->id);
      } else {
         $this->flash('Your feed creation failed. '.
                      'Please check all fields and try again; contact an administrator if all else fails.','error');
         redirect_to(ADMIN_URL.'/feeds/new');
      }
   }

   function updateAction()
   {
      $feed = new Feed($this->args[1]);
      $dat = $_POST['feed'];
      $feed->name = $dat['name'];
      $feed->description = $dat['description'];
      $feed->group_id = $dat['group'];

      if($feed->set_properties()) {
         $this->flash('Feed Updated Successfully');
         redirect_to(ADMIN_URL.'/feeds/show/'.$feed->id);
      } else {
         $this->flash('Feed update failed. Please try again.','error');
         redirect_to(ADMIN_URL.'/feeds/show/'.$this->args[1]);
      }
   }

   function destroyAction()
   {
      $feed = new Feed($this->args[1]);
      if($feed->destroy()) {
         $this->flash('Feed destroyed successfully');
         redirect_to(ADMIN_URL.'/feeds');
      } else {
         $this->flash('There was an error removing the feed.','error');
         redirect_to(ADMIN_URL.'/feeds/show/'.$this->args[1]);
      }
   }
}
?>
