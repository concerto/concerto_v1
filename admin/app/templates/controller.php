<?php
class templatesController extends Controller
{
   public $actionNames = Array( 'list'=> 'Content Listing', 'show'=>'Details',);

   public $require = Array( 'require_login'=>Array('index') );

   function setup()
   {
      $this->setName("Content");
      $this->setTemplate("blank_layout", "preview");
   }
   function indexAction()
   {
      $this->listAction();
      $this->renderView("content", "list");
   }
  
   function listAction()
   {
   }

   function previewAction()
   {
      $res = sql_select('template','filename','id='.$this->args[1]);
      $this->file = TEMPLATE_DIR.$res[0]['filename'];
      $this->fields = sql_select('field', Array('id','name','type_id','`left`','top','width','height'),'template_id='.$this->args[1]);
      $this->act_field = $this->args[2];
      
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