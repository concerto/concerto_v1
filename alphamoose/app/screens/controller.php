<?php
class screensController extends Controller
{
   public $actionNames = Array( 'list'=> 'Screens Listing', 'show'=>'Details');
   
   public $require = Array( 'check_login'=>1,
                            'require_login'=>1,
                            'require_action_auth'=>Array('require_admin'=>
                                                         Array('edit','create',
                                                               'new', 'update' ) ) );
   
   function setup()
   {
      $this->setName("Screens");
   }
   
   function indexAction()
   {
      $this->listAction();
      $this->renderView("screens", "list");
   }
   
   function listAction()
   {
      $screenids = sql_select("screen","mac_address");
      $this->screens=Array();
      if(is_array($screenids))
         foreach($screenids as $screen)
            $this->screens[] = new Screen($screen[mac_address]); 
   }
   
   function showAction()
   {
      $this->screen = new Screen($this->args[1]);
      $this->setTitle($this->screen->name);
   }
   
   function editAction()
   {
      $this->screen = new Screen($this->args[1]);
      $this->setTitle("Editing ".$this->screen->name);
   }
   
   function newAction()
   {
      $this->setTitle("Create new screen");
   }
   
   function createAction()
   {
   }
   
   function updateAction()
   {
   }
   
   function destroyAction()
   {
   }
   
}
?>
