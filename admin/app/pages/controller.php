<?php
class pagesController extends Controller
{
   public $actionNames = Array( 'list'=> 'Page Listing', 'show'=>'Read',
                                'edit'=> 'Edit', 'delete'=>'Delete', 'new'=>'New');

   public $require = Array( 'require_action_auth'=>Array('edit','create', 'up', 'dn',
                                                         'new', 'update',
                                                         'delete', 'destroy' ) );

   function setup()
   {
      $this->setName("Pages");
   }

   function indexAction()
   {
      $this->listAction();
      $this->renderView("pages", "list");
   }

   function listAction()
   {
      $this->canEdit = isAdmin();
      $this->pages = sql_select('page',Array('page.id', 'page.name', 'page_category.path', 'page_category.name AS cat'),null,"LEFT JOIN `page_category` ON `page_category`.`id` = `page_category_id` ".
                                "ORDER BY `page_category_id`,`order`");
   }

   function showAction()
   {
      $this->canEdit = isAdmin();
      if(isset($this->args[1])) {
         list($this->category) = sql_select('page_category','*','path LIKE \''.
                                            escape($this->args[1]).'\'');

         if(is_numeric($this->args[2])) {
            list($this->page) = sql_select('page','*','id = '.escape($this->args[2]).
                                           ' AND page_category_id = '.$this->category['id']);
         } elseif(is_numeric($this->category['id'])) {
            list($this->page) = sql_select('page','*','page_category_id = '.$this->category['id'],
                                           'ORDER BY `order` ASC LIMIT 1');
         }
      }
      if(isset($this->category['id']))
         $this->menu_links = sql_select('page',Array('id as url','name'),'page_category_id = '.
                                        $this->category['id'],'ORDER BY `order` ASC');

      if(isset($this->page['id'])) {
         $this->setTitle($this->page['name']);
         $this->setSubtitle('Last updated on '.
                            date('F j, Y',strtotime($this->page['timestamp'])));
         if(isset($this->category['layout']))
            $this->template=$this->category['layout'];
         $this->setSubject($this->getTitle());
      } else {
         if(isset($this->category['id']))
            redirect_to("../");
         else
            redirect_to(ADMIN_URL);
      }
   }
   
   function editAction()
   {
      if(is_numeric($this->args[1]))
         list($this->page)=sql_select('page','*','id='.$this->args[1]);
      if(!is_numeric($this->page['id']))
         redirect_to(ADMIN_URL.'/pages');
      $this->setSubject($this->page['name']);
      $this->setTitle("Editing ".$this->page['name']);
   }

   function newAction()
   {
      $this->setTitle("Create new page");
   }

   function deleteAction()
   {
      if(is_numeric($this->args[1])) {
         list($page) = sql_select('page',Array('page.name','page.id','page_category.name AS cat'),null,'LEFT JOIN `page_category` ON page_category.id=page_category_id '.
                                        'WHERE page.id = '.escape($this->args[1]));
         $this->listAction();
         $this->renderView('list');
         $this->setTitle('Deleting '.$page['name']);
         $this->flash("Do you really want to remove <strong>{$page['name']}</strong> from {$page['cat']}? <br />".
                      '<a href="'.ADMIN_URL.'/pages/destroy/'.$page['id'].'">Yes</a> | '.
                      '<a href="'.ADMIN_URL.'/pages/">No</a>','warn');
      }
   }

   function createAction()
   {
      $cat=$_POST['page']['category'];
      $name=escape($_POST['page']['name']);
      $content=escape($_POST['page']['content']);
      $uid = $_SESSION['user']->id;
      if(is_numeric($cat) && is_string($name) && is_string($content) && is_numeric($uid)) {
         list($last_item) = sql_select('page','`order`',"`page_category_id` = $cat",'ORDER BY `order` DESC LIMIT 1');
         $order=$last_item['order']+1;
         $res = sql_command("INSERT INTO `page` (`page_category_id` ,`name` ,`content` ,`user_id` ,`timestamp` ,`order`) ".
                            "VALUES ('$cat', '$name', '$content', '$uid', NOW( ) , '$order');");
      }
      if($res>0) {
         $this->flash($name.' was created successfully.');
         redirect_to(ADMIN_URL.'/pages/');
      } else {
         $this->flash('Your page creation failed. '.
                      'Please check all fields and try again.','error');
         redirect_to(ADMIN_URL.'/pages/new');
      }
   }

   function updateAction()
   {
      $id = $this->args[1];
      $cat=$_POST['page']['category'];
      $name=escape($_POST['page']['name']);
      $content=escape($_POST['page']['content']);
      $uid = $_SESSION['user']->id;
      if(is_numeric($id) && is_numeric($cat) && is_string($name) && is_string($content) && is_numeric($uid)) {
         $res = sql_command("UPDATE `page` SET `page_category_id`='$cat', `name`='$name', ".
                            "`content`='$content', `user_id`='$uid' ,`timestamp`=NOW() ".
                            "WHERE id=$id LIMIT 1");
      }
      if($res>0) {
         $this->flash($name.' was updated successfully.');
         redirect_to(ADMIN_URL.'/pages/');
      } else {
         $this->flash('Your page update failed. '.
                      'Please check all fields and try again.','error');
         redirect_to(ADMIN_URL.'/pages/edit');
      }
   }

   function upAction()
   {
      $id = $this->args[1];
      if(is_numeric($id)) {
         list($page) = sql_select('page',Array('id', 'page_category_id', '`order`', 'name'),
                                  "id = $id");
         $cat = $page['page_category_id'];
         $old_order = $page['order'];
         list($last_item) = sql_select('page',Array('id','`order`'),"`page_category_id` = $cat ".
                                       "AND `order` < $old_order",
                                       'ORDER BY `order` DESC LIMIT 1');
         if(is_array($last_item) && count($last_item)>1) {
            $new_order = $last_item['order'];
            $res = sql_command("UPDATE page SET `order` = $new_order WHERE id = '".
                               $id.'\' LIMIT 1');
            if($res==1) 
               $res2 = sql_command("UPDATE page SET `order` = $old_order WHERE id='".
                                   $last_item['id'].'\' LIMIT 1');
         }
      }
      
      if($res2>0) {
         $this->flash($page['name'].' was moved up successfully.');
         redirect_to(ADMIN_URL.'/pages/');
      } else {
         $this->flash('Your page move failed. '.
                      'Please try again.','error');
         redirect_to(ADMIN_URL.'/pages/edit');
      }
   }

   function dnAction()
   {
      $id = $this->args[1];
      if(is_numeric($id)) {
         list($page) = sql_select('page',Array('id', 'page_category_id', '`order`', 'name'),
                                  "id = $id");
         $cat = $page['page_category_id'];
         $old_order = $page['order'];
         list($last_item) = sql_select('page',Array('id','`order`'),"`page_category_id` = $cat ".
                                       "AND `order` > $old_order",
                                       'ORDER BY `order` ASC LIMIT 1');
         if(is_array($last_item) && count($last_item)>1) {
            $new_order = $last_item['order'];
            $res = sql_command("UPDATE page SET `order` = $new_order WHERE id = '".
                               $id.'\' LIMIT 1');
            if($res==1) 
               $res2 = sql_command("UPDATE page SET `order` = $old_order WHERE id='".
                                   $last_item['id'].'\' LIMIT 1');
         }
      }
      
      if($res2>0) {
         $this->flash($page['name'].' was moved down successfully.');
         redirect_to(ADMIN_URL.'/pages/');
      } else {
         $this->flash('Your page move failed. '.
                      'Please try again.','error');
         redirect_to(ADMIN_URL.'/pages/edit');
      }
   }

   function destroyAction()
   {
      $id = $this->args[1];
      if(is_numeric($id))
         $res = sql_command('DELETE FROM `page` WHERE `page`.`id` = '.escape($id));
      if($res) {
         $this->flash('Page destroyed successfully');
         redirect_to(ADMIN_URL.'/pages');
      } else {
         $this->flash('There was an error removing the page.','error');
         redirect_to(ADMIN_URL.'/pages');
      }
   }
}
