<?php
class usersController extends Controller
{
   public $actionNames = Array( 'list'=> 'Users Listing', 'show'=>'Details', 'newsfeed'=>'News Feed',
                                'edit'=> 'Edit', 'signup'=>'Create Profile', 'new'=>'New');

   public $require = Array( 'require_login'=>Array('index' ,'list','show'),
                           'require_action_auth'=>Array('edit', 'new', 
                                                        'update', 'destroy', 'newsfeed', 'notifications') );
   //note: it is only with great care that we don't have any requirements to create or signup

   function setup()
   {
      $this->setName("Users");
      $this->setTemplate('blank_layout', Array('notifications'));
   }

   function indexAction()
   {
      $this->listAction();
      $this->renderView("users", "list");
   }
   
   function listAction()
   {
      $userids = sql_select("user","username");
      $this->users=Array();
      if(is_array($userids))
         foreach($userids as $user)
            $this->users[] = new User($user[username]); 
   }
   
   function showAction()
   {
      $this->user = new User($this->args[1]);
      if(strlen($this->user->username)<1)
      {
         $this->flash('The user you requested could not be found. '.
                      'You may have found a bad link, or the user may no longer be in the system.',
                      'error');
         redirect_to(ADMIN_URL.'/users');
      }
      $this->setTitle($this->user->name);
      $this->setSubject($this->user->name);
//    $this->canEdit =$_SESSION['user']->can_write('user',$this->args[1]);
      $this->canEdit = has_action_auth('users',$this->user->id);
      $this->groups=array();
      if($this->user->admin_privileges)
         $this->groups[]= "<strong>Concerto Administrators</strong>";
      $group_objs=$this->user->list_groups();
      if(is_array($group_objs))
         foreach($this->user->list_groups() as $group)
            $this->groups[] = '<a href="'.ADMIN_URL."/groups/show/$group->id\">$group->name</a>";

      $types = sql_select('type',Array('id','name'), NULL, 'ORDER BY name');
      foreach($types as $type) {
         $contentids = sql_select('feed_content','DISTINCT content_id', '', 'INNER JOIN `content`'.
      ' ON content_id=content.id AND moderation_flag=1 AND type_id = '.$type['id'].
      ' AND content.user_id='.$this->user->id.' ORDER BY name');
         if(is_array($contentids))
            foreach($contentids as $id)
               $this->contents[$type['name']][] = new Content($id['content_id']);
      }
      $this->notifications = Newsfeed::get_for_user($this->user->id, 0);
   }
   
   function editAction()
   {
      $this->user = new User($this->args[1]);
      $this->setTitle("Editing profile for ".$this->user->name);
      $this->setSubject($this->user->name);
   }
   
   function signupAction()
   {
      if(!phpCAS::isAuthenticated())
         redirect_to(ADMIN_URL.'/frontpage/login');
      if(isLoggedIn())
         redirect_to(ADMIN_URL.'/users/');
      $this->user = new User();
      $this->user->username = phpCAS::getUser();
   }

   function newAction()
   {
      $this->setTitle("Create new user profile");
   }

   function createAction()
   {
      $dat = $_POST['user'];
      $user = new User();
      
      if(!phpCAS::isAuthenticated()) {
         redirect_to(ADMIN_URL.'/users/signup');
         exit();
      }
      if(isAdmin()) {
         if($user->create_user($dat['username'],$dat['name'],
                               $dat['email'],$dat['admin_privileges']=='admin'?1:0, $dat['allow_email']=='allow'?1:0)) {
            $_SESSION['flash'][]=Array('info', 'User profile created successfully.');
            redirect_to(ADMIN_URL.'/users/show/'.$user->username);
         } else {
            $_SESSION['flash'][]=Array('error', 'Your profile submission failed. '.
                                       'Please check all fields and try again.');
            redirect_to(ADMIN_URL.'/users/new');
         }
      } else {
         if($user->create_user(phpCAS::getUser(),$dat['name'],
                               $dat['email'],0,$dat['allow_email']=='allow'?1:0)) {
            $_SESSION['flash'][]=
               Array('info','Your profile was created successfully. Welcome to Concerto!');
            login_login();
            redirect_to(ADMIN_URL);
         } else {
            $_SESSION['flash'][]=Array('error', 'Your profile submission failed. '.
                                       'Please check all fields and try again.');
            redirect_to(ADMIN_URL.'/users/signup');
         }
      }
   }

   function updateAction()
   {
      $user = new User($this->args[1]);
      $dat = $_POST['user'];
   
      //We don't want anyone modifying these properties
      //of their own profiles
      if($_SESSION[user]->username != $user->username) {
         $user->username = $dat['username'];
         $user->admin_privileges = $dat['admin_privileges']=='admin'?1:0;
      }
      $user->name = $dat['name'];
      $user->email = $dat['email'];
      $user->allow_email = $dat['allow_email']=='allow'?1:0;
   
      if($user->set_properties()) {
         $_SESSION['flash'][]=Array('info', 'User profile updated successfully.');
         redirect_to(ADMIN_URL.'/users/show/'.$user->username);
      } else {
         $_SESSION['flash'][]=Array('error', 'Your submission failed. Please check all fields and try again.');
         redirect_to(ADMIN_URL.'/users/show/'.$this->args[1]);
      }
   }

   function notificationsAction()
   {
      $start = $_REQUEST['start'] ? $_REQUEST['start'] : 0;
      $num = $_REQUEST['num'] ? $_REQUEST['num'] : 999999999999;
      $userid = $_REQUEST['user'] ? $_REQUEST['user'] : $_SESSION['user']->id;
      if(isAdmin() || $_SESSION['user']->id == $userid)
         $this->notifications = Newsfeed::get_for_user($userid , 0, '', $start, $num);
   }

   function newsfeedAction()
   {
      if($user = new User($this->args[1])) {
         $this->setSubject($user->name);         
         $this->num=25;
         $this->page=$this->args[2]?$this->args[2]:0;
         $this->start = $this->num*$this->page;
         $this->notification_count = Newsfeed::count_for_user($user->id);  
         $this->notifications = Newsfeed::get_for_user($user->id , 0, '', $this->start, $this->num);
      } else {
         $this->flash("User not found.");
         redirect_to(ADMIN_URL.'/users/');
      }
   }

   function destroyAction()
   {
   }   
}
?>
