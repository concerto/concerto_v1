<?php
class frontpageController extends Controller
{
	public $actionNames = Array( 'index'=> "Front page", 
'stupid'=>'Stupid Page');

   public $require = Array('check_login'=>1,
                           'require_login'=>Array('login') );

	function setup()
	{
		$this->setName("Home");
	}

	function indexAction()
	{
		$this->setTitle("Digital Signage Interface");
	}

	function stupidAction()
	{
		$this->setTitle("Idiot.");
	}

	function loginAction()
	{
		global $sess;
		$_SESSION['flash'][] = '<a href="#">Random Authed Page</a>';
		self::renderView('frontpage');
	}

	function logoutAction()
	{
		login_logout();
		$_SESSION['flash'][] = array('warn','Something went wrong with your logout. Close your browser to end the session securely.');
		self::renderView('frontpage');
	}
}
?>
