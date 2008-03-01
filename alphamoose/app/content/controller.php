<?php
class contentController extends Controller
{
   public $actionNames = Array( 'list'=> 'Content Listing', 'show'=>'Details',
                                'edit'=> 'Edit');

   public $require = Array( 'require_login'=>Array('index','list','show'),
                            'require_action_auth'=>Array('edit', 'new', 
                                                         'update', 'destroy', 'create') );

   function setup()
   {
      $this->setName("Content");
      $this->setTemplate("blank_layout", "image");
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
         $contentids = sql_select('content','id','type_id = '.$type['id'],
                                  'ORDER BY name');
         if(is_array($contentids))
            foreach($contentids as $id)
               $this->contents[$type['name']][] = new Content($id[id]);
      }
    }

   function imageAction()
   {
      $content = new Content($this->args[1]);
      if($content->mime_type = 'image/jpeg') {
         $this->file = CONTENT_DIR .'/'. $content->content;
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
      $this->setTitle($this->content->name);
      $this->canEdit =$_SESSION['user']->can_write('content',$this->args[1]);
   }
   //up to here   
   function editAction()
   {
      $this->user = new User($this->args[1]);
      $this->setTitle("Editing profile for ".$this->user->name);
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
               Array('info','Your profile was created successfully. Welcome to concerto!');
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
