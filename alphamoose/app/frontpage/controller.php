<?php
class frontpageController extends Controller
{
	public $actionNames = Array( 'index'=> "Front page", 
'stupid'=>'Stupid Page');

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
		$this->setTitle("Frontpage");
	}

	function loginAction()
	{
		global $sess;
		$sess['breadcrumbs'][] = '<a href="#">Random Authed Page</a>';
		if(!requireLoggedIn()) return true;
		self::renderView('frontpage');
	}
	function logoutAction()
	{
		global $sess;
		login_logout();
		$sess['message'][] = array('warn','Something went wrong with 
your logout. Close your browser to end the session securely.');
		self::renderView('frontpage');
	}
}
?>
