<?php

/**
 * config.inc.php
 * 
 * Concerto system configuration - where people can't get to it
 */

//Database Connection
$db_host = 'rpisenate.com';
$db_login = 'concerto_dev';
$db_password = 'phe7rudr';
$db_database = 'concerto_development';

//Important paths
define('ROOT_DIR', '/var/www/ditore/');             //server-side path where Concerto lives
define('COMMON_DIR', ROOT_DIR.'common/');        //server-side path to dir with resources for
                                                 //  multiple portions of Concerto
define('CONTENT_DIR', '/var/www/signage/content/');//server-side path to content images
define('IMAGE_DIR', CONTENT_DIR.'images/');      //server-side path to content images
define('TEMPLATE_DIR', CONTENT_DIR.'templates/');//server-side path to screen templates

//URLS for hyperlinks and the like
define('ROOT_URL', '/ditore/');                    //the root location where Concerto lives
define('SCREEN_URL', ROOT_URL.'screen/');        //location of front-end screen program
define('HARDWARE_URL', ROOT_URL.'hardware/');    //location of management for on-location machines
define('ADMIN_BASE_URL', ROOT_URL.'admin/');     //base URL on server for images, css, etc. for interface
define('ADMIN_URL', ROOT_URL.'admin/index.php');           //URL that can access this page (may be same as ADMIN_BASE_URL if mod_rewrite configured)

//Various configuration
define('CONCERTO_VERSION', '1.7 dev');           //Version number.
define('DEFAULT_DURATION', 5);                   //Default content duration, in seconds
define('DEFAULT_WEIGHT', 3);                     //Default position weight

define('EMS_FEED_ID', 19);                       //ID of the emergency feed.
define('ADMIN_GROUP_ID', 0);                     //ID of User Group for admin functionality
define('GA_TRACKING', false);                    //Define a Google Tracking id if applicable
define('TICKER_LIMIT', 150);                     //Ticket Text character limit
define('SYSTEM_EMAIL', 'concerto@union.rpi.edu');//Email address used for system emails
?>
