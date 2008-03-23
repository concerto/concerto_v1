<?php
class usersController extends Controller
{
   public $actionNames = Array( 'list'=> 'Users Listing', 'show'=>'Details',
                                'edit'=> 'Edit', 'signup'=>'Create Profile', 'new'=>'New');

   public $require = Array( 'require_login'=>1,
                            'require_action_auth'=>Array('list', 'edit', 'new', 
                                                         'update', 'destroy', 'show') );
   //note: it is only with great care that we don't have any requirements to create or signup

   function setup()
   {
      $this->setName("Users");
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
      $this->canEdit =$_SESSION['user']->can_write('user',$this->args[1]);
      $this->groups=array();
      if($this->user->admin_privileges)
         $this->groups[]= "<strong>Concerto Administrators</strong>";
      $group_objs=$this->user->list_groups();
      if(is_array($group_objs))
         foreach($this->user->list_groups() as $group)
            $this->groups[] = '<a href="'.ADMIN_URL."/groups/show/$group->id\">$group->name</a>";
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
                               $dat['email'],$dat['admin_privileges']=='admin'?1:0)) {
            $_SESSION['flash'][]=Array('info', 'User profile created successfully.');
            redirect_to(ADMIN_URL.'/users/show/'.$user->username);
         } else {
            $_SESSION['flash'][]=Array('error', 'Your profile submission failed. '.
                                       'Please check all fields and try again.'.print_r($dat,true).mysql_error());
            redirect_to(ADMIN_URL.'/users/new');
         }
      } else {
         if($user->create_user(phpCAS::getUser(),$dat['name'],
                               $dat['email'],0)) {
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
   
      if($user->set_properties()) {
         $_SESSION['flash'][]=Array('info', 'User profile updated successfully.');
         redirect_to(ADMIN_URL.'/users/show/'.$user->username);
      } else {
         $_SESSION['flash'][]=Array('error', 'Your submission failed. Please check all fields and try again.');
         redirect_to(ADMIN_URL.'/users/show/'.$this->args[1]);
      }
   }

   function destroyAction()
   {
   }   
}
?>
