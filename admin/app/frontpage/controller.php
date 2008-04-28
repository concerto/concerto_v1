<?php
class frontpageController extends Controller
{
	public $actionNames = Array( 'index'=> "Front page", 
'admin'=>'Admin Utilities');

   public $require = Array('check_login'=>Array('dashboard','login','logout'),
                           'require_login'=>Array('admin','login','dashboard','su','phpinfo') );

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
      $this->screens= Screen::get_all();
	}

	function adminAction()
	{
      $_SESSION['flash'][] = Array('error', "This is an error.");
      $_SESSION['flash'][] = Array('warn', "This is a warning.");
      $_SESSION['flash'][] = Array('info', "FYI");
      $_SESSION['flash'][] = Array('stat', "status");
		$this->setTitle("Administrative Utilities");
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
         $_SESSION['user']=$user;
      } elseif ($user->admin_privileges  && isset($_REQUEST['su'])) {
         $_SESSION['user'] = new User($_REQUEST['su']);
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
