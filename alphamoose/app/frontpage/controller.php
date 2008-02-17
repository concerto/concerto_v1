<?Php
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
		$this->setTitle("Concerto Front Page");
	}

	function stupidAction()
	{
		$this->setTitle("Idiot.");
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
