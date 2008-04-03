<?php
class screensController extends Controller
{
   public $actionNames = Array( 'list'=> 'Screens Listing', 'show'=>'Details',
                                'edit'=> 'Edit', 'new'=>'New', 'subscriptions'=>'Subscriptions');

   public $require = Array( 'require_login'=>1,
                            'require_action_auth'=>Array('edit','create',
                                                         'new', 'update',
                                                         'delete','destroy',
                                                         'subscriptions', 'subscribe') );

   function setup()
   {
      $this->setName('Screens');
   }

   function indexAction()
   {
      $this->listAction();
      $this->renderView('screens', 'list');
   }

   function listAction()
   {
      $this->screens=Screen::get_all();
   }

   function showAction()
   {
      $this->screen = new Screen($this->args[1]);
      $this->setTitle($this->screen->name);
      $this->setSubject($this->screen->name);
      $this->canEdit =$_SESSION['user']->can_write('screen',$this->args[1]);
   }

   function editAction()
   {
      $this->screen = new Screen($this->args[1]);
      $this->setTitle('Editing '.$this->screen->name);
      $this->setSubject($this->screen->name);
   }

   function subscriptionsAction()
   {
      $this->screen = new Screen($this->args[1]);
      $this->setTitle('Managing Subscriptions for '.$this->screen->name);
      $this->setSubject($this->screen->name);
      $this->feeds=Feed::get_all();

      $res = sql_select('template','*','id='.$this->screen->template_id);
      $this->template = $res[0];
   }
   function newAction()
   {
      $this->setTitle('Create new screen');
   }

   function createAction()
   {
      $this->setTitle('Screen Creation');
      $screen=new Screen();

      if($screen->create_screen($_POST[screen][name],$_POST[screen][group],$_POST[screen][location],$_POST[screen][mac_inhex],
		$_POST[screen][width],$_POST[screen][height],$_POST[screen][template])) {
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
           $field->rebalance_scale();
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
     $dat = $_POST['screen'];
     $screen->name = $dat['name'];
     $screen->group_id = $dat['group'];
     $screen->location = $dat['location'];
     $screen->mac_inhex = $dat['mac_inhex'];
     $screen->width = $dat['width'];
     $screen->height = $dat['height'];
     $screen->template_id = $dat['template'];

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
      $this->flash("Do you really want to remove <strong>{$this->screen->name}</strong>? <br />".
                   '<a href="'.ADMIN_URL.'/screens/destroy/'.$this->screen->id.'">Yes</a> | '.
                   '<a href="'.ADMIN_URL.'/screens/show/'.$this->screen->id.'">No</a>','warn');
   }

   function destroyAction()
   {
      $screen = new Screen($this->args[1]);
      if($screen->destroy()) {
         $this->flash('Screen removed successfuly');
         redirect_to(ADMIN_URL.'/screens');
      } else {
         $this->flash('There was an error removing the screen.','error');
         redirect_to(ADMIN_URL.'/screens/show/'.$this->args[1]);
      }
   }
}
?>
