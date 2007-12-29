<?php
   /*Mike DiTore's CAS Login stuff
    *This allows CAS login functionality and is where all client interaction
    *takes place as far as login/logout/access control is concerned.
    *
    *Most logic has not been updated, only the login method as of yet
    *
    *This is not currently a replacement for ds's original login.php,
    *though that is what I'm aiming for eventually
    *
    *last edited by mike, during the hour of 2007-11-04 0200
    */
   include("/var/www/ds/config/config.php");
   include('CAS/CAS.php');
   phpCAS::client(CAS_VERSION_2_0,'login.rpi.edu',443,'/cas');
   /*
    potential params
    ?logout
    ?denied
   */
   
   if(isset($_REQUEST['logout']))
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


   function login_logout()
   {
      $_SESSION = array();
      session_destroy();
      session_start();
      header("Cache-control: private"); // IE 6 Fix
      phpCAS::logout();
   }

   function login_denied()
   {
      echo "<h1>ACCESS DENIED.</h1>\n";
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
                $res = mysql_query($query);

                if ( $row = mysql_fetch_assoc($res) ) {
                        if ( $row['active'] == 1 ) {
                                $_SESSION['LOGGED_IN'] = 1;
                                $_SESSION['LOGIN_ATTEMPTS'] = 0;
                                $_SESSION['USERNAME'] = $row['username'];
                                $_SESSION['ID'] = $row['id'];
                                $_SESSION['PRIV_LEVEL'] = $row['priv_code'];

      header("Location: ../"); //go to frontpage


                        } else {
                                //error(WARNING,"Account not yet activated.");
                                echo "Account not yet acivated.";
                                //return loginForm();
                        }
                } else {
                        login_denied();
                }
                
                
   }
