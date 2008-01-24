<?php
include('includes/mysql.inc');
include('includes/login.php');
include('classes/screen.php');

define('ADMIN_BASE_URL','/mike_admin');
define('ADMIN_URL','/mike_admin/index.php');
define('DEFAULT_CONTROLLER','frontpage');
define('DEFAULT_TEMPLATE','ds_layout');
define('HOMEPAGE','Signage Interface');
define('HOMEPAGE_URL', ADMIN_URL);
define('APP_PATH','app');
//session variables visible to both controller and view
//global $sess;
global $rddesp;

//parse request
$request = split('/',trim($_SERVER[PATH_INFO],'/'));

//decide what controller we'll be requesting an action from
$controller = $request[0];
if(!isset($controller) || $controller == "") $controller = 
DEFAULT_CONTROLLER;

//include the code for the requested controller
if(!file_exists(APP_PATH.'/'.$controller.'/controller.php')) {
	notFound();
} else {
  include(APP_PATH.'/'.$controller.'/controller.php');

   // make a reflection object to represent our controller
   $reflectionObj = new ReflectionClass($controller.'Controller');

   // use Reflection to create a new instance
   $controllerObj = $reflectionObj->newInstanceArgs(); 

   //have the controller do its thing
   $controllerObj->execute(array_slice($request,1));
}

//Send headers.  They should not be sent before now.
/*switch($httpStatus)
{
	case 404:
	header('HTTP/1.0 404 Not Found'); break;

	case 401:
	header('HTTP/1.0 401 Unauthorized'); break;

	case 403:
	header('HTTP/1.0 403 Forbidden'); beak;

	case 200:
	default:
        header("Cache-control: private"); // IE 6 Fix
        if(!isset($contentType))
                $contentType = "text/html";
        header("Content-Type: {$contentType}; charset=ISO-8859-1");
	break;
}*/

//layout the page.  This will call renderAction when
//  it is time to render the view.
//if(file_exists($sess[pageTemplate].'.php'))
//	include ($sess[pageTemplate].'.php');
//else
//	renderAction(); //failsafe

//to be called by layout, this renders the main content of the page
function renderAction()
{
	global $qview, $sess;
	$file = APP_PATH.'/'.$qview[0].'/'.$qview[1].'.php';
}

//print out the statuse messages saved in $sess
function renderMessages()
{
	global $sess;
	if(is_array($sess[messages]))
		foreach($sess[messages] as $msg)
			echo renderMessage($msg[0], $msg[1]);
}

function notFound()
{
   global $sess;
   $status = 404;
 //  setView(BLANK_VIEW,0);
}

function denied($reason=0)
{
    global $sess;
    $status = 403;
    switch ($reason) {
        case 0:
          $rtext='Permission denied.'; break;
        case 'login':
          $rtext='You must be logged in to view this page.'; 
	  $status = 401; break;
        case 'rights':
          $rtext='You have insufficient rights for this action.'; break;
        default:
          $rtext='Permission denied: '.$reason;
    }

    $sess['messages'][] = array('warn',$rtexto);
    if($reason == 'login')
      $sess['messages'][] = array('info','<a href="?login">Log in</a> or <a href= "'.
    ADMIN_BASE_URL.'/help">visit the help pages</a> to learn more.');
    setView('frontpage','denied');
   }


//ancestor for all controllers
class Controller
{
	protected $defaultAction = 'index';
	protected $before_execs = array();
	protected $after_execs = array();
	protected $defaultTemplate = DEFAULT_TEMPLATE;
	protected $templates = array();
	protected $controller;
	function __construct()
	{
		$this->controller = ereg_replace('Controller','',get_class($this));
		$this->setup();
	}

	function setup() //meant to be overriden by child
	{
		return false;
	}

	function execute($args)
	{
		$breadcrumbs[]='<a href="'.HOMEPAGE_URL.'">'.HOMEPAGE.'</a>';
		$breadcrumbs[]='<a href="'.ADMIN_URL.'/'.$controller.'">'.
			$this->getName().'</a>';
	
		if(method_exists($this,$args[0].'Action'))
			$action = $args[0];
		else if(method_exists($this, $this->defaultAction.'Action'))
			$action = $this->defaultAction;
		else
			notFound();
	
		//save information about view to display
		//(may be modified by action)
		$this->view[controller]=$this->controller;
		$this->view[view]=$action;
	
		//find the action's name
		if($action != $this->defaultAction) {
			$actionName = $this->actionNames[$action];
			if(!isset($actionName)) $actionName = $action;
			$breadcrumbs[]='<a 
href="'.ADMIN_URL.'/'.$controller.'/'.$action.'">'.
			$actionName.'</a>';
		}	

		//run the action
		
//call_user_func(array(get_class($this),$action.'Action'));
		
call_user_func(array($this,$action.'Action'));

		//Deal with the page's title
		$pageTitle = $this->getTitle();

		//include the template, which will call back for view
		$template = $this->getTemplate($action);
		if($template !== false)
			include $template;
		else //if this occurs, a 404 will be delivered but the
		     //action may still have been completed.
			notFound(); 
	}
	
	//renders (directly outputs) the view; to be called by template
	function render()
	{
		$viewpath=APP_PATH.'/'.
			$this->view[controller].'/'.
			$this->view[view].'.php';
		if(file_exists($viewpath))
			include($viewpath);
	}
	function setName($name)
	{
		$this->name = $name;
	}
	function getName()
	{
		return $this->name;
	}
	function setTitle($title)
	{
		$this->pageTitle=$title;
	}
	function getTitle()
	{
		if(isset($this->pageTitle))
			return $this->pageTitle;
		if(isset($this->actionName[$this->view[view]]))
			return $this->actionName[$this->view[view]];
		if(isset($this->controller))
			return $this->controller;
	}

	function setTemplate($template, $actions=0)
	{
		if($actions == 0)
			$this->defaultTemplate=$template;
		else if(is_array($actions))
			foreach ($actions as $action)
				$this->templates[$action] = $template;
		else
			$this->templates[$actions] = $template;
		return true;
	}

	function getTemplate($action)
	{
		if(isset($this->templates[$action]) && 
			file_exists($this->templates[$action].'.php'))
			return $this->templates[$action].'.php';
		else if(file_exists($this->defaultTemplate).'.php')
			return $this->defaultTemplate.'.php';
		return false;
	}

	function getDefaultAction()
	{
		return $this->defaultAction;
	}

	function renderView($a1, $a2=-1)
	{
		global $sess;
		if ($a2==-1)
		{
			global $controller;
			$view = $a1;
		}
		else
		{
			$controller = $a1;
			$view = $a2;
		}
		//setView($controller, $view);
	}
}
?>
