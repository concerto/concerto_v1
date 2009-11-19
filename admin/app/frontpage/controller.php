<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technologies Group)
 *
 * This program is free software; you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.  You should have received a copy
 * of the GNU General Public License along with this program.
 *
 * @package      Concerto
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */
class frontpageController extends Controller
{
	public $actionNames = Array( 'index'=> "Front page", 
                                'admin'=>'Admin Utilities',
                                'mailer' =>'Send Mail',
                                'addtemplate' =>'Upload Template',
                                'dashboard' => 'Dashboard');

   public $require = Array('check_login'=>Array('dashboard','login','logout'),
                           'require_login'=>Array('admin','login','dashboard','su','phpinfo','mailer','sendmail','addtemplate','createtemplate') );

	function setup()
	{
		$this->setName("Home");
	}

	function indexAction()
	{
          $this->setTitle("Front Page");

          #When the frontpage controller is not handling the frontpage,
          #i.e. the frontpage is a dynamic page, we will redirect to the
          #top URL so that the framework can handle serving the frontpage.
          #All dashboard references should go to frontpage/dashboard.
          
          if(defined('DEFAULT_PATH') && DEFAULT_PATH != '/frontpage') {
            redirect_to(ADMIN_URL);
            exit(0);
          }
          
          if (isLoggedIn()) {
             $this->dashboardAction();
             $this->renderView('dashboard');
          }
	}
   
	function dashboardAction()
	{
          $this->notifications = Newsfeed::get_for_user($_SESSION['user']->id);
          $this->setTitle("Concerto Dashboard");
     $group_str = implode(',',$_SESSION['user']->groups);
     $this->setTitle("Dashboard");
     if(count($_SESSION['user']->groups) > 0){
        $group_str = 'OR group_id IN (' . $group_str . ')';
     } else {
        $group_str = "";
     }
     $this->screens= Screen::get_all('WHERE type = 0 ' . $group_str . ' ORDER BY `name`');
     if(!is_array($this->screens)){
       $this->screens = array();
     }
     $this->screen_stats = Screen::screenStats('WHERE type = 0 ' . $group_str . ' ORDER BY `name`');
	}

	function adminAction()
	{
      $user = new User($_SESSION['user']->username);
      if(!$user->admin_privileges)
         redirect_to(ADMIN_URL.'/frontpage');

      $this->flash('This is an error.','error');
      $this->flash('This is a warning.','warn');
      $this->flash('Status','stat');
      $this->flash('FYI','info');
      $this->flash('Default message type');

		$this->setTitle("Administrative Utilities");

      if(isset($_REQUEST['stats'])) {
         if($_REQUEST['stats']=='Turn On') {
            $_SESSION['stats']=1;
            $_SESSION['flash']='Page build statistics now on (see page bottom)';
         } else {
            $_SESSION['stats']=0;
            $_SESSION['flash']='Page build statistics now off';
         }
      }
	}
	function mailerAction()
	{
         $user = new User($_SESSION['user']->username);
         $this->fromyou = $user->name . ' (' . $user->email . ')';
         if(!$user->admin_privileges)
           redirect_to(ADMIN_URL.'/frontpage');

  	 $this->setTitle("System Mailman");
         //Generate Users
	 $userids = sql_select("user","username",false,"ORDER BY username");
	 $this->users = array();
	 foreach($userids as $username){
	   $this->users[] = new User($username['username']);
	 }
         //Generate Groups
         $groupids = sql_select("group","id",false,"ORDER BY name");
         $this->groups = array();
         foreach($groupids as $groupid){
           $this->groups[] = new Group($groupid['id']);
         }

	}
	function sendmailAction()
	{
	     $curuser = new User($_SESSION['user']->username);
	     if(!$curuser->admin_privileges)
         redirect_to(ADMIN_URL.'/frontpage');

	     $message = $_POST['message'];
	     $subject = $_POST['subject'];
             $from = '';
             if($_POST['from'] == 'user'){
	       $from = $curuser->name . ' <' . $curuser->email . '>';
	     }
	     if($message <= "" || $subject <= ""){
	       $this->flash('Emails must have a subject and message.','error');
	       redirect_to(ADMIN_URL.'/frontpage/mailer');
	     }
	     if(isset($_POST['everyone'])){
		$userrows = sql_select('user', 'username');
		foreach($userrows as $row){
		  $users[] = $row['username'];
		}
	     } else { //We are not sending to everyone
	       $users = array();
	       //Handle individual users
	       $usernames = $_POST['user'];
	       if(sizeof($usernames) > 0) {
	         $users = array_merge($users,$usernames);
	       }

	       //Handle groups & special groups
	       $groupids = array();
	       if(isset($_POST['group'])){
	         $groupids = $_POST['group'];
	       }
	       if(isset($_POST['special'])){ //Handle special groups that own stuff
	         $special = $_POST['special'];
	         foreach($special as $table){
		    $members = sql_select($table, 'group_id');
		    foreach($members as $member){
		      $groupids[] = $member['group_id'];
		    }
	         }
	       }
	       $groupids = array_unique($groupids);
	       if(sizeof($groupids) > 0){
	         foreach($groupids as $groupid){
	           $group = new Group($groupid);
	           $group_users = $group->list_members();
	           $users = array_merge($users, $group_users);
	         }
	       }
	       $users = array_unique($users);
	     } //End big block to build the recipients array
	     if(sizeof($users) == 0){
	       $this->flash('Emails must at least one recipient.','error');
	       redirect_to(ADMIN_URL.'/frontpage/mailer');
	     }
	     $status = true;
	     foreach($users as $user){
	       $user = new User($user);
	       $retval = $user->send_mail($subject, $message, $from);
	       $status = $status * $retval; 
	     }
	     if(!$status){
	       $this->flash('The mail function returned false, some messages may not have gotten delivered.','warn');
	     } else {
	       $this->flash('It appears the messages were sucessfully sent.','info');
	     }
	     redirect_to(ADMIN_URL.'/frontpage/mailer');
	}

   function addtemplateAction(){
    $user = new User($_SESSION['user']->username);
      if(!$user->admin_privileges)
         redirect_to(ADMIN_URL.'/frontpage');
         
     $this->setTitle("Upload Template");
   }
   function createtemplateAction(){
    $user = new User($_SESSION['user']->username);
    if(!$user->admin_privileges)
       redirect_to(ADMIN_URL.'/frontpage');
         
     $file['name']=$_FILES['template']['name']['image'];
     $file['type']=$_FILES['template']['type']['image'];
     $file['tmp_name'] = $_FILES['template']['tmp_name']['image'];
     $file['error'] = $_FILES['template']['error']['image'];
     $file['size'] = $_FILES['template']['size']['image'];
     
     $xml = @simplexml_load_file($_FILES['template']['tmp_name']['descriptor']);
     if($xml == false){
       $this->flash("Descriptor parsing error, check XML syntax.",'error');
       redirect_to(ADMIN_URL.'/frontpage/addtemplate');
     }
     $template = new Template();
     $status = $template->create_template((string)$xml->name, (int)$xml->height, (int)$xml->width, $file, (string)$xml->author);
    // print_r($template); print_r($xml); print_r($file);
     if($status){
       foreach($xml->field as $data){
         $status = $status * $template->add_field((string)$data->name, (string)$data->type, (string)$data->style, (string)$data->left, (string)$data->top , (string)$data->width, (string)$data->height);
       }
       if($status){
         redirect_to(ADMIN_URL.'/templates/preview/' . $template->id . '?width=800');
       } else {
         //One of the fields failed;
        $this->flash($template->status . "  One or more of the field descriptors failed.  You'll want to jump into the DB to see what happened. This error was not handled gracefully.",'error');
        redirect_to(ADMIN_URL.'/frontpage/addtemplate');
       }
     } else {
       //Unable to create template
        $this->flash($template->status . "  The template failed to create.  Check your descriptor syntax and image file size.",'error');
        redirect_to(ADMIN_URL.'/frontpage/addtemplate');
     }
   }

   function miniscreenAction()
   {
     $this->template="blank_layout.php";

     $this->graphics = Content::get_all('LEFT JOIN feed_content ON content.id = feed_content.content_id ' . 
                                        'LEFT JOIN feed ON feed_content.feed_id = feed.id ' .
                                        'WHERE feed_content.moderation_flag = 1 AND content.type_id = 3 '. 
                                        'AND feed.type != 3 AND content.start_time < NOW() AND content.end_time > NOW() AND content.mime_type LIKE "%image%" ' .
                                        'ORDER BY RAND()');
     $this->ticker = Content::get_all('LEFT JOIN feed_content ON content.id = feed_content.content_id ' .
                                      'LEFT JOIN feed ON feed_content.feed_id = feed.id ' .
                                      'WHERE feed_content.moderation_flag = 1 AND content.type_id = 2 AND feed.type != 3 AND content.start_time < NOW() AND content.end_time > NOW() ' .
                                      'ORDER BY RAND()');
     $this->text = Content::get_all('LEFT JOIN feed_content ON content.id = feed_content.content_id ' .
                                    'LEFT JOIN feed ON feed_content.feed_id = feed.id ' .
                                    'WHERE feed_content.moderation_flag = 1 AND content.type_id = 1 AND feed.type != 3 AND content.start_time < NOW() AND content.end_time > NOW() ' .
                                    'ORDER BY RAND()');
  
  }
   
   function phpinfoAction()
   {
      $user = new User($_SESSION['user']->username);
      if(!$user->admin_privileges)
         redirect_to(ADMIN_URL.'/frontpage');
      phpinfo();
      exit();
   }

   function suAction()
   {
      $user = new User($_SESSION['user']->username);
      if(isset($_REQUEST['r'])) {
         unset($_SESSION['su']);
         login_login();
      } elseif ($user->admin_privileges  && isset($_REQUEST['su'])) {
         $_SESSION['su']=$_REQUEST['su'];
         login_login();
      }
      redirect_to(ADMIN_URL."/frontpage");
   }

	function loginAction()
	{
      redirect_to(ADMIN_URL."/frontpage/dashboard");
	}

	function logoutAction()
	{
		login_logout();
		$_SESSION['flash'][] = array('warn','Something went wrong with your logout. Close your browser to end the session securely.');
		self::renderView('frontpage');
	}
}
?>
