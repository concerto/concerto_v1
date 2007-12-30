<?php
class frontpageController extends Controller
{
	function getName()
	{
		return "Home";
	}

	function index()
	{
		global $sess;
		$sess['pagetitle'] = 'Digital Signage Interface';
		self::renderView('frontpage');
	}
	function login()
	{
		global $sess;
		$sess['breadcrumbs'][] = '<a href="#">Random Authed Page</a>';
		requireLoggedIn();
		self::renderView('frontpage');
	}
	function logout()
	{
		global $sess;
		login_logout();
		$sess['message'][] = array('warn','Something went wrong with 
your logout. Close your browser to end the session securely.');
		self::renderView('frontpage');
	}
}
?>
