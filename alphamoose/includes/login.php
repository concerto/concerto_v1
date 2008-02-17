<?php

/*Mike DiTore's CAS Login stuff
 *This allows CAS login functionality and is where all client interaction
 *takes place as far as login/logout/access control is concerned.
 *It should be included in every page that uses login.
 *
 *Nearing full functionality when used with the framework.
 *
 *last edited by mike, probably recently.
 */

//Get and setup the CAS client
include('CAS/CAS.php');
phpCAS::client(CAS_VERSION_2_0,'login.rpi.edu',443,'/cas');

//the following functions are accessors to the login functionality
//they are designed for use as "requirements" of site actions
//should return true, or perform some action before returning;
//false indicates an error.
function check_login($callback)
{
   if(isLoggedIn())
      return true;

   if($callback->controller == 'users' && $callback->action == 'create')
      return true;

   //   if(phpCAS::isAuthenticated()) {
   //    login_login();
   //}

   if(phpCAS::checkAuthentication())
      login_login();
}

function require_login()
{
   phpCAS::forceAuthentication();
   if(!isLoggedIn())
      login_login();
   return true;
}

function require_action_auth($callback)
{
   check_login($callback);
   $target = $callback->controller;
   $id=$callback->currId;

   if(!has_action_auth($target, $id)) {
      $_SESSION[flash][] = Array('error',"Sorry, you don't have permission to edit $target $id");
      redirect_to(ADMIN_URL.'/'.$callback->controller);
   }

   return true;
}

//these methods are interfaces to logon information.
function isLoggedIn()
{
   if(strlen($_SESSION['user']->username)>1) return true;
   return false;
}

function isAdmin()
{
   if($_SESSION['user']->admin_privileges) return true;
   return false;
}

function firstName()
{
   return $_SESSION['user']->firstname;
}

function userName()
{  
   return $_SESSION['user']->username;
}

function has_action_auth($target, $id)
{
   if(!isLoggedIn()) return false;
   $grant=false;

   if($target=='users') {
      $target='user';
      $grant=(isAdmin() || 
              (phpCAS::isAuthenticated() && $id==phpCAS::getUser()));
   } else {
      if($target=='screens') $target='screen';
      elseif($target=='feeds') $target='feed';
      if($_SESSION['user']->can_write($target,$id)) 
         $grant=true;
   }
   return $grant;
}

//login/out functionality

function login_logout()
{
   $_SESSION = array();
   session_destroy();
   session_start();
   header("Cache-control: private"); // IE 6 Fix
      phpCAS::logout();
}

function login_login()
{
   // force CAS authentication
   phpCAS::forceAuthentication();

   // at this step, the user has been authenticated by the CAS server
   // and the user's login name can be read with phpCAS::getUser().   
   $rcsid = phpCAS::getUser();
   $rcsid=mysql_escape_string($rcsid);
   $_SESSION['user'] = new user($rcsid);
   if($_SESSION['user']->username != $rcsid){
      redirect_to(ADMIN_URL.'/users/signup');
      exit();
   }
}
