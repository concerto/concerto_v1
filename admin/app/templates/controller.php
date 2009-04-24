<?php
class templatesController extends Controller
{
   public $actionNames = Array( 'list'=> 'Content Listing', 'show'=>'Details',);

   public $require = Array( 'require_login'=>Array('index') );

   function setup()
   {
      $this->setName('Template');
      $this->setTemplate('blank_layout', Array('preview', 'image'));
   }

   function indexAction()
   {
      $this->listAction();
      $this->renderView("template", "list");
   }
   
   function listAction()
   {
   }

   function previewAction()
   {
      $this->p_template = new Template($this->args[1]);
      if(!$this->p_template->set){
        return false;
      }
      
      $this->act_field = $this->args[2];
      
      $this->width = '400';
      $this->height = '300';
      if(isset($_REQUEST['width'])) {
         $this->width = $_REQUEST['width'];
      }
      if(isset($_REQUEST['height'])) {
         $this->height = $_REQUEST['height'];
      }
   }
   
   function imageAction()
   {
      $res = sql_select('template','filename','id='.$this->args[1]);
      $this->file = $res[0]['filename'];
      $this->width = '400';
      $this->height = '300';
      if(isset($_REQUEST['width'])) {
         $this->width = $_REQUEST['width'];
      }
      if(isset($_REQUEST['height'])) {
         $this->width = $_REQUEST['height'];
      }
   }

}
?>
