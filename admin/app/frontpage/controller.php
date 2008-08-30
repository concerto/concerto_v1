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
