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
function check_login()
{
   if(isLoggedIn())
      return true;
   if(phpCAS::checkAuthentication())
      login_login();
}

function require_login()
{
   phpCAS::forceAuthentication();
   if(isLoggedIn())
      return true;
   return false;
}

function require_action_auth($callback)
{
   check_login();
   $target = $callback->controller;
   $id=$callback->currId;

   if($target=='screens') $target='screen';
   if($target=='feeds') $target='feed';

   if($_SESSION['user']->can_write($target,$id)) return true;
   else {
      $_SESSION[flash][] = Array('error',"Sorry, you don't have permission to access $target $id");
      redirect_to(ADMIN_URL."/screens");
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
   $nm=split(" ",$_SESSION['user']->username);
   return $nm[0];
}

function userName()
{  
   return $_SESSION['user']->username;
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
//   print_r($_SESSION['user']);
   if($_SESSION['user'] === false){
      echo "You don't have an account yet.  A form is coming soon.";
      exit();
   }
}
