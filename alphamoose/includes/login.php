<?php
   /*Mike DiTore's CAS Login stuff
    *This allows CAS login functionality and is where all client interaction
    *takes place as far as login/logout/access control is concerned.
    *It should be included in every page that uses login.
    *
    *Nearing full functionality when used with the framework.
    *
    *last edited by mike, during the hour of 2007-12-29 1500
    */
//   include('mysql.inc');
   include('CAS/CAS.php');
   phpCAS::client(CAS_VERSION_2_0,'login.rpi.edu',443,'/cas');

   if( isset($_GET['login']) || (!isLoggedIn()&&phpCAS::checkAuthentication()) )
   {
     login_login();     
   }

   function isLoggedIn()
   {
     if($_SESSION['LOGGED_IN']==1) return true;
     return false;
   }

   function isAdmin()
   {
     if($_SESSION['IS_ADMIN']==1) return true;
     return false;
   }

   function firstName()
   {
	$nm=split(" ",$_SESSION["FULL_NAME"]);
	return $nm[0];
   }

   function userName()
   {  
     return $_SESSION['RCSID'];
   }
/*   if(isset($_REQUEST['logout']))
   {
      login_logout();
   } 
   else if($_REQUEST['denied'])
   {
      login_denied();
   }
   else if($_SESSION['LOGGED_IN'] == 1) //we're logged in
   {
      header("Location: ../"); //go to frontpage
   }
   else
   {
      login_login();
   }

*/
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

     // get the username
     $rcsid = phpCAS::getUser();
     $rcsid=mysql_escape_string($rcsid);

     //clearly, the following is from the old login.  I may change this yet.
                $query = "SELECT * FROM user WHERE username='$rcsid'";
                $res = sql_query($query);

                if ( $row = sql_row_keyed($res,0) ) {
                   $_SESSION['LOGGED_IN'] = 1;
                   $_SESSION['IS_ADMIN'] = $row['admin_privileges'];
                   $_SESSION['RCSID'] = $row['username'];
                   $_SESSION['FULL_NAME'] = $row['name'];
                } else {
                        //login_denied();
                        echo "You don't have an account yet.  A form is coming 
soon.";
                        exit();
                }                
   }
