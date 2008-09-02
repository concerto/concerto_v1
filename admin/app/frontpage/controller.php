<?php
class frontpageController extends Controller
{
	public $actionNames = Array( 'index'=> "Front page", 
                                'admin'=>'Admin Utilities',
                                'mailer' =>'Send Mail');

   public $require = Array('check_login'=>Array('dashboard','login','logout'),
                           'require_login'=>Array('admin','login','dashboard','su','phpinfo','mailer','sendmail') );

	function setup()
	{
		$this->setName("Home");
	}

	function indexAction()
	{
      $this->setTitle("Concerto Front Page");
      if (isLoggedIn()) {
         $this->dashboardAction();
         $this->renderView('dashboard');
      }
	}
   
	function dashboardAction()
	{
     $this->setTitle("Concerto Dashboard");
     $this->screens= Screen::get_all('ORDER BY `name`');
     $this->screen_stats = Screen::screenStats();
	}

	function adminAction()
	{
      $user = new User(phpCAS::getUser());
      if(!$user->admin_privileges)
         redirect_to(ADMIN_URL.'/frontpage');

      $this->flash('This is an error.','error');
      $this->flash('This is a warning.','warn');
      $this->flash('Status','stat');
      $this->flash('FYI','info');
      $this->flash('Default message type');

		$this->setTitle("Administrative Utilities");

      if(isset($_REQUEST['stats'])) {
         if($_REQUEST['stats']=='Turn On') {
            $_SESSION['stats']=1;
            $_SESSION['flash']='Page build statistics now on (see page bottom)';
         } else {
            $_SESSION['stats']=0;
            $_SESSION['flash']='Page build statistics now off';
         }
      }
	}
	function mailerAction()
	{
         $user = new User(phpCAS::getUser());
         $this->fromyou = $user->name . ' (' . $user->email . ')';
         if(!$user->admin_privileges)
           redirect_to(ADMIN_URL.'/frontpage');

  	 $this->setTitle("System Mailman");
         //Generate Users
	 $userids = sql_select("user","username",false,"ORDER BY username");
	 $this->users = array();
	 foreach($userids as $username){
	   $this->users[] = new User($username['username']);
	 }
         //Generate Groups
         $groupids = sql_select("group","id",false,"ORDER BY name");
         $this->groups = array();
         foreach($groupids as $groupid){
           $this->groups[] = new Group($groupid['id']);
         }

	}
	function sendmailAction()
	{
	     $curuser = new User(phpCAS::getUser());
	     $message = $_POST['message'];
	     $subject = $_POST['subject'];
             $from = '';
             if($_POST['from'] == 'user'){
	       $from = $curuser->name . ' <' . $curuser->email . '>';
	     }
	     if($message <= "" || $subject <= ""){
	       $this->flash('Emails must have a subject and message.','error');
	       redirect_to(ADMIN_URL.'/frontpage/mailer');
	     }
	     if(isset($_POST['everyone'])){
		$userrows = sql_select('user', 'username');
		foreach($userrows as $row){
		  $users[] = $row['username'];
		}
	     } else { //We are not sending to everyone
	       $users = array();
	       //Handle individual users
	       $usernames = $_POST['user'];
	       if(sizeof($usernames) > 0) {
	         $users = array_merge($users,$usernames);
	       }

	       //Handle groups & special groups
	       $groupids = array();
	       if(isset($_POST['group'])){
	         $groupids = $_POST['group'];
	       }
	       if(isset($_POST['special'])){ //Handle special groups that own stuff
	         $special = $_POST['special'];
	         foreach($special as $table){
		    $members = sql_select($table, 'group_id');
		    foreach($members as $member){
		      $groupids[] = $member['group_id'];
		    }
	         }
	       }
	       $groupids = array_unique($groupids);
	       if(sizeof($groupids) > 0){
	         foreach($groupids as $groupid){
	           $group = new Group($groupid);
	           $group_users = $group->list_members();
	           $users = array_merge($users, $group_users);
	         }
	       }
	       $users = array_unique($users);
	     } //End big block to build the recipients array
	     if(sizeof($users) == 0){
	       $this->flash('Emails must at least one recipient.','error');
	       redirect_to(ADMIN_URL.'/frontpage/mailer');
	     }
	     $status = true;
	     foreach($users as $user){
	       $user = new User($user);
	       $retval = $user->send_mail($subject, $message, $from);
	       $status = $status * $retval; 
	     }
	     if(!$status){
	       $this->flash('The mail function returned false, some messages may not have gotten delivered.','warn');
	     } else {
	       $this->flash('It appears the messages were sucessfully sent.','info');
	     }
	     redirect_to(ADMIN_URL.'/frontpage/mailer');
	}

   function phpinfoAction()
   {
      $user = new User(phpCAS::getUser());
      if(!$user->admin_privileges)
         redirect_to(ADMIN_URL.'/frontpage');
      phpinfo();
      exit();
   }

   function suAction()
   {
      $user = new User(phpCAS::getUser());
      if(isset($_REQUEST['r'])) {
         unset($_SESSION['su']);
         login_login();
      } elseif ($user->admin_privileges  && isset($_REQUEST['su'])) {
         $_SESSION['su']=$_REQUEST['su'];
         login_login();
      }
      redirect_to(ADMIN_URL."/frontpage");
   }

	function loginAction()
	{
      redirect_to(ADMIN_URL."/frontpage");
	}

	function logoutAction()
	{
		login_logout();
		$_SESSION['flash'][] = array('warn','Something went wrong with your logout. Close your browser to end the session securely.');
		self::renderView('frontpage');
	}
}
?>
