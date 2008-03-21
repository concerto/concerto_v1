<?php

/**
 * config.inc.php
 * 
 * Concerto system configuration - where people can't get to it
 */

//Important paths
define('ROOT_DIR', '/var/www/ds');                    //server-side path where Concerto lives
define('COMMON_DIR', ROOT_DIR.'/common');             //server-side path to dir with resources for
                                                      //  multiple portions of Concerto
define('CONTENT_DIR', ROOT_DIR.'/content/images');    //server-side path to content images
define('TEMPLATE_DIR', ROOT_DIR.'/content/templates');//server-side path to screen templates

//URLS for hyperlinks and the like
define('ROOT_URL', '/');                         //the root location where Concerto lives
define('SCREEN_URL', ROOT_URL.'screen');         //location of front-end screen program
define('HARDWARE_URL', ROOT_URL.'hardware');     //location of management for on-location machines
define('ADMIN_BASE_URL', ROOT_URL.'admin');      //base URL on server for images, css, etc. for interface
define('ADMIN_URL', ADMIN_BASE_URL.'/index.php');//URL that can access this page (may be same as
                                                 //  ADMIN_BASE_URL if mod_rewrite configured)

//Various configuration
define('DEFAULT_DURATION', 5);                   //Default content duration, in seconds

?>
