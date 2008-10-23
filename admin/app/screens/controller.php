<?php
class screensController extends Controller
{
   public $actionNames = Array( 'list'=> 'Screens Listing', 'show'=>'Details',
                                'edit'=> 'Edit', 'new'=>'New', 'subscriptions'=>'Subscriptions',
                                'livecd'=>'New LiveCD Screen');

   public $require = Array( 'require_login'=>Array('index','list','show','edit','subscriptions','new','create','subscribe',
                                                   'update','delete','destroy','livecd'),
                            'require_action_auth'=>Array('edit','create',
                                                         'new', 'update',
                                                         'delete','destroy',
                                                         'subscriptions', 'subscribe') );

   function setup()
   {
      $this->setName('Screens');
      $this->setTemplate('blank_layout','powerstate');
      $this->setTemplate('livecd_layout','livecd');

   }

   function indexAction()
   {
      $this->listAction();
      $this->renderView('screens', 'list');
   }

   function listAction()
   {
      $group_str = implode(',',$_SESSION['user']->groups);
      if(count($_SESSION['user']->groups) > 0){
        $group_str = 'OR group_id IN (' . $group_str . ')';
      } else {
        $group_str = "";
      }
      $this->screens=Screen::get_all('WHERE type = 0 ' . $group_str . ' ORDER BY `name`');
      if(!is_array($this->screens)){
        $this->screens = array();
      }
   }

   function showAction()
   {
      $this->screen = new Screen($this->args[1]);
      if(!$this->screen->set) {
         $this->flash("Screen not found","error");
         redirect_to("../");
      }
      $this->setTitle($this->screen->name);
      $this->setSubject($this->screen->name);
      $this->canEdit =$_SESSION['user']->can_write('screen',$this->args[1]);
   }

   function editAction()
   {
      $this->screen = new Screen($this->args[1]);
      if(!$this->screen->set) {
         $this->flash("Screen not found","error");
         redirect_to("../");
      }
      $this->setTitle('Editing Settings for '.$this->screen->name);
      $this->setSubject($this->screen->name);
   }

   function subscriptionsAction()
   {
      $this->screen = new Screen($this->args[1]);
      if(!$this->screen->set) {
         $this->flash("Screen not found","error");
         redirect_to("../");
      }
      $this->setTitle('Managing Subscriptions for '.$this->screen->name);
      $this->setSubject($this->screen->name);
      $this->feeds=Feed::get_all();

      $res = sql_select('template','*','id='.$this->screen->template_id);
      $this->templateobj = $res[0]; //Template is a keyword for the controller, so I call it something else
   }
   function newAction()
   {
      $this->setTitle('Create new screen');
   }
   
   function templateAction()
   {
      $this->screen = new Screen($this->args[1]);
      if(!$this->screen->set) {
         $this->flash("Screen not found","error");
         redirect_to("../");
      }
      $this->setTitle('Change Screen Template for '.$this->screen->name);
      $this->setSubject($this->screen->name);
   }

   function createAction()
   {
      $this->setTitle('Screen Creation');
      $screen=new Screen();

      if($screen->create_screen($_POST[screen][name],$_POST[screen][group],$_POST[screen][location],$_POST[screen][mac_inhex],
		$_POST[screen][width],$_POST[screen][height],$_POST[screen][template],0,$_POST[screen][latitude],$_POST[screen][longitude])) {
         $this->flash($screen->name.' was created successfully.');
         redirect_to(ADMIN_URL.'/screens/show/'.$screen->id);
      } else {
         $this->flash('The screen creation failed. '.
                      'Please check all fields and try again; contact an administrator if all else fails.','error');
         redirect_to(ADMIN_URL.'/screens/new');
      }
   }

   function subscribeAction()
   {
     $screen = new screen($this->args[1]);
      if(!$screen->set) {
         $this->flash("Screen not found","error");
         redirect_to("../");
      }
     $dat = $_POST['content']['freq'];

     $success = true;

     $fields =$screen->list_fields();
     if(is_array($fields)){
        foreach($fields as $field){
           $posits = $field->list_positions();
           //update or remove existing positions
           if(is_array($posits)){ 
              foreach($posits as $pos) {
                 if(isset($dat[$field->id][$pos->feed_id])) {
                    $pos->weight=$dat[$field->id][$pos->feed_id];
                    unset($dat[$field->id][$pos->feed_id]);
                 } else {
                    $pos->destroy();
                 }   
              }
           }
           //add new positions
           if(is_array($dat[$field->id])) {
              foreach($dat[$field->id] as $feed_id=>$wt) {
                 if($field->add_feed($feed_id)) {
                    foreach($field->list_positions() as $pos) {
                       if($pos->feed_id==$feed_id)
                          $pos->weight=$wt;
                    }
                 } else $success=false;
              }
           }
           if($field->set_properties()===false) $success=false;
        }
     }
     
     if($success) {
        $this->flash('Screen subscriptions updated successfully!');
        redirect_to(ADMIN_URL.'/screens/subscriptions/'.$screen->id);
     } else {
        $this->flash('There was an error updating the screen. Please try again, or contact an administrator.','error');
        redirect_to(ADMIN_URL.'/screens/subscriptions/'.$this->args[1]);
     }
   }

   function updateAction()
   {
     $screen = new screen($this->args[1]);
      if(!$screen->set) {
         $this->flash("Screen not found","error");
         redirect_to("../");
      }
     $dat = $_POST['screen'];
     $screen->name = $dat['name'];
     $screen->group_id = $dat['group'];
     $screen->location = $dat['location'];
     $screen->mac_inhex = $dat['mac_inhex'];
     $screen->width = $dat['width'];
     $screen->height = $dat['height'];
     $screen->template_id = $dat['template'];
     $screen->latitude = $dat['latitude'];
     $screen->longitude = $dat['longitude'];
     if(isAdmin()) {
        $screen->controls_display = $dat['controls_display'];
     }
     $screen->time_on = $dat['time_on'];
     $screen->time_off = $dat['time_off'];

     if($screen->set_properties()) {
        $this->flash('Screen updated successfully!');
        redirect_to(ADMIN_URL.'/screens/show/'.$screen->id);
     } else {
        $this->flash('Your submission was not valid. Please try again.','error');
        redirect_to(ADMIN_URL.'/screens/show/'.$this->args[1]);
     }
   }

   function deleteAction()
   {
      $this->showAction();
      $this->renderView('show');
      $this->setTitle('Deleting '.$this->screen->name);
      $this->flash("Do you really want to remove the screen <strong>{$this->screen->name}</strong>? <br />".
                   '<a href="'.ADMIN_URL.'/screens/destroy/'.$this->screen->id.'">Yes</a> | '.
                   '<a href="'.ADMIN_URL.'/screens/show/'.$this->screen->id.'">No</a>','warn');
   }

   function destroyAction()
   {
      $screen = new Screen($this->args[1]);
      if($screen->destroy()) {
         $this->flash('Screen removed successfully.');
         redirect_to(ADMIN_URL.'/screens');
      } else {
         $this->flash('There was an error removing the screen.','error');
         redirect_to(ADMIN_URL.'/screens/show/'.$this->args[1]);
      }
   }
   
   /* Interface for power management checks by screens.
    */
   function powerstateAction()
   {
      //Challenge string for generating signature
      $this->challenge=$_GET['challenge_string'];

      //Optional hour and minute parameters for checking & testing
      $h = isset($_GET['h']) ? $_GET['h'] : -1;
      $m = isset($_GET['m']) ? $_GET['m'] : -1;
      
      $screen = new Screen($_GET['mac'],true);

      if ($this->is_emergency()) {
         // All screens go ON when EMS is active
         //echo "It's an Emergency!";
         $this->status = true;
      } else if($screen->set) {
         // What does $screen->set do ???
         $this->status = $screen->get_powerstate($h, $m);
         //$this->status = true;
      } else {
         $this->status = false;
      }
   }

   private function is_emergency(){
      // shamelessly ripped off from common/driver.php and modified slightly
      if(defined('EMS_FEED_ID') && EMS_FEED_ID != 0){
        $ems_feed = new Feed(EMS_FEED_ID);
        if($ems_feed->content_count(1) > 0){
           return true;
        } else {
           //The feed is empty.  All is quiet on the western front
           return false;
        }
      } else {
        //EMS hasn't been setup
        return false;
      }
   }
   function livecdAction(){
      if(!defined("LIVE_CD_SUPPORT") || LIVE_CD_SUPPORT == false){
        $this->flash('Live CD support has not been configured yet.  Please contact an administrator.','error');
        redirect_to(ADMIN_URL);
      }
   
     $this->livecd['width'] = $_REQUEST['w'];
     $this->livecd['height'] = $_REQUEST['h'];
     $this->livecd['mac'] = $_REQUEST['mac'];

     $this->groups = $_SESSION['user']->list_groups();
     $this->nogroups = false;
     if(!is_array($this->groups)){
       $this->flash('You do not belong to any groups permitted to register live cd screens on the system.  Please contact an administrator for more information.','error');
       $this->nogroups = true;
     }
     
   
   }
    function livecdcreateAction(){
    
        if(!defined("NOTIF_OFF")){ //Lets keep liveCD screens private for now
          define("NOTIF_OFF",1);
        }
        
        $this->setTitle('Screen Creation');
        $screen=new Screen();
        
        //Defaults for live cds
        /*
        $default_template = 22;
        $default_subscriptions[73][] = 0;
        $default_subscriptions[74][] = 1;
        $default_subscriptions[74][] = 30;
        $default_subscriptions[75][] = 8;
        $default_subscriptions[75][] = 11;
        */
        
        if(!isset($default_template) || !isset($default_subscriptions) || !is_array($default_subscriptions)){
            $this->flash('Live CD support has not been configured yet.  Please contact an administrator.','error');
            redirect_to(ADMIN_URL);
        }
        
        if($screen->create_screen($_POST[screen][name],$_POST[screen][group],$_POST[screen][location],$_POST[screen][mac_inhex],$_POST[screen][width],$_POST[screen][height],$default_template,1)){
            
            //Now setup some sample subscriptions
            foreach($default_subscriptions as $field_id => $subscriptions){
              foreach($subscriptions as $feed_id){
                $pos = new Position;
                $pos->create_position($screen->id, $feed_id, $field_id);
              }
            }
           
            
            redirect_to(ROOT_URL. '/?mac='. $_POST[screen][mac_inhex]. '&w=' .$_POST[screen][width]. '&h=' . $_POST[screen][height] . '&livecd=1');
      } else {
         $this->flash('The screen creation failed. '.
                      'Please check all fields and try again; contact an administrator if all else fails.','error');
         redirect_to(ADMIN_URL.'/screens/livecd?mac='. $_POST[screen][mac_inhex]. '&w=' .$_POST[screen][width]. '&h=' . $_POST[screen][height] . '&livecd=1');
      }
   
   }
}
?>
