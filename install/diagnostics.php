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
 * @author       Web Technologies Group, $Author: brian $
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision: 558 $
 */
?>
<html>
  <head>
    <title>Concerto Diagnostics</title>
    <style type="text/css">
      .pass{
        color: green;
      }
      .fail{
        color: red;
        font-weight: bold;
      }
    </style>
  </head>
  <body>
    <p>This page checks some system settings and configuration options to help judge if Concerto will work as intended.  It is by no means exhaustive or all inclusive.</p>
    <h1>PHP Enviroment</h1>
    <ul>
      <li>PHP Version: <?= php_v(); ?></li>
      <li>MySQL Support: <?= mysql_ext();?></li>
      <li>GD Support: <?= gd_ext();?></li>
      <li>JSON Support: <?= json_test();?></li>
      <li>PATH INFO Global: <?= path_info(); ?></li>
    </ul>
    <h1>Concerto Config</h1>
    <ul>
      <li>Config access: <?= config_found(); ?></li><? include('../config.inc.php'); ?>
      <li>Image Upload Directory: <?= image_dir(); ?></li>
      <li>Template Upload Directory: <?= template_dir(); ?></li>
      <li>MySQL Connection test: <?= test_mysql(); ?></li>
    </ul>
  </body>
</html>
<?php
function php_v(){
  if (floatval(phpversion()) >= 5.2){
    return pass(phpversion());
  } else {
    return fail(phpversion());
  }
}

function mysql_ext(){
  if(extension_loaded('mysql')){
    return pass("Ok");
  } else {
    return fail("Missing MySQL extension");
  }
}

function gd_ext(){
  if(extension_loaded('gd')){
    $info = gd_info();
    return pass($info["GD Version"]);
  } else {
    return fail("Missing GD extension");
  } 
}

function json_test(){
  if(!function_exists('json_encode') || !function_exists('json_decode')){
    return fail("JSON support missing");
  } else {
    return pass("OK");
  }
}

function path_info(){
  if(string_ends_with($_SERVER['PHP_SELF'], 'diagnostics.php')){
    return fail('Unable to test.  Try visiting <a href="' . $_SERVER['PHP_SELF']. '/test">this</a>');
  } else {
    if(isset($_SERVER['PATH_INFO'])){
      return pass($_SERVER['PATH_INFO']);
    } else {
      return fail("PATH INFO not exposed");
    }
  }
}

function config_found(){
  $p = '../config.inc.php';
  if(!file_exists($p)){
    return fail("Missing config.inc.php");
  } elseif (!is_readable($p)){
    return fail("Cannot read file");
  } else {
    return pass("OK");
  }
}

function image_dir(){
  if(!is_writable(IMAGE_DIR)){
    return fail("Cannot write to " . IMAGE_DIR);
  } else {
    return pass("OK");
  }
}

function template_dir(){
  if(!is_writable(TEMPLATE_DIR)){
    return fail("Cannot write to " . TEMPLATE_DIR);
  } else {
    return pass("OK");
  }
}

function test_mysql(){
  include('../config.inc.php'); //Make the DB login stuff local
  include('../common/mysql.inc.php');
  return pass("OK");
}

function pass($str){
  return "<span class='pass'>PASS ($str)</span>";
}

function fail($str){
  return "<span class='fail'>FAIL $str</span>";
}

function string_ends_with($string, $ending){
  $len = strlen($ending);
  $string_end = substr($string, strlen($string) - $len);
  return $string_end == $ending;
}
?>