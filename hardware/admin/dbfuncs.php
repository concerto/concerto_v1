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

require("config.php");
require_once("signature.php");

function init_db( ) {    
    mysql_pconnect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
        or die("MySQL connect failed! error = " . mysql_error( ));
    mysql_select_db(MYSQL_DATABASE)
        or die("MySQL select database failed: " . mysql_error( ));
}

function validate_mac($mac) {
    $mac = str_replace(':','',$mac); # remove any colons from the mac address
    if (preg_match('/^[0-9A-Fa-f]{12}$/', $mac)) {
        return $mac;
    } else {
        return 0;
    }
}

class FlashFile {
    private $id, $name, $md5, $sig, $url;

    public function create_new($name, $location, $url) {
        # compute md5 sum of the data file
        $md5 = md5_file($location);
        # sign the md5 hash
        $sig = generate_signature($md5);
        # insert into database
        $name = mysql_escape_string($name);
        mysql_query(
            "insert into file (name, md5, sig, url) ".
            "values(\"$name\", \"$md5\", \"$sig\", \"$url\")"
        ) or die ("query to insert new file failed: " . mysql_error( ));

        # return the object
        $new_id = mysql_insert_id( );
        return FlashFile::load_from_id($new_id);
    }
    public function load_from_id($id) {
        $obj = new FlashFile( );
        if (!is_numeric($id)) {
            die("passed non-numeric ID to load_from_id");
        }
        $result = mysql_query("select name, md5, sig, url from file where file_id=$id") 
            or die("query to load file object from DB failed: " . mysql_error( ));

        if (!($row = mysql_fetch_row($result))) {
            die("attempt to load file object with nonexistent ID\n");
        } else {
            $obj->id = $id;
            $obj->name = $row[0];
            $obj->md5 = $row[1];
            $obj->sig = $row[2];
            $obj->url = $row[3];
        }
    }
    public function get_name( ) {
        return $this->name;
    }
    public function get_url( ) {
        return $this->url;
    }
    public function get_md5( ) {
        return $this->md5;
    }
    public function get_sig( ) {
        return $this->sig;
    }
    public function get_id( ) {
        return $this->id;
    }
    public function delete( ) {
        $id = $this->id;
        mysql_query("delete from file where file_id=$id");
        mysql_query("delete from file_map where file_id=$id");
    }
}

class HardwareClass {
    public function create_new($name) {
        // create a new hardware class and return it.
        // escape the string first in case it contains special chars
        $name = mysql_escape_string($name);
        mysql_query("insert into class (name) values(\"$name\")")
            or die("query to create new class failed: " . mysql_error( ));
        $new_id = mysql_insert_id( );
        return HardwareClass::load_from_id($new_id);
    }
    public function load_all_from_db( ) {
        // load all classes from the database
        $result = mysql_query("select class_id from class")
            or die("query to load class list failed: " . mysql_error( ));
        
        $objs = array( );
        while ($row = mysql_fetch_row($result)) {
            array_push($objs, HardwareClass::load_from_id($row[0]));
        }

        return $objs;
    }
    
    public function load_from_id($id) {
        // load a hardware class from the database given its ID
        $obj = new HardwareClass( );
        if (!is_numeric($id)) {
            die("passed non-numeric ID to load_from_id");
        }
        
        $result = mysql_query("select name from class where class_id=$id")
            or die("query to load ID failed: " . mysql_error( ));
        
        if (!($row = mysql_fetch_row($result))) {
            die("attempt to load nonexistent class $id");
        } else {
            $obj->id = $id;
            $obj->name = $row[0];
            return $obj;
        }
    }

    public function find_from_mac($mac) {
        if (($mac = validate_mac($mac)) === 0) {
            die("invalid MAC address passed");
        }

        $mac = mysql_escape_string($mac);
        $result = mysql_query("select class_id from class_map where mac='$mac'")
            or die("query to get class from MAC failed: " . mysql_error( ));

        if ($row = mysql_fetch_row($result)) {
            return HardwareClass::load_from_id($row[0]);
        } else {
            return 0;
        }
    }

    public function get_member_list( ) {
        // load the list of member MAC addresses from the database
        // returns an array of MAC strings
        $id = $this->id;
        $result = mysql_query("select mac from class_map where class_id=$id")
            or die("query to load member list failed: " . mysql_error( ));

        $ret = array( );

        while ($row = mysql_fetch_row($result)) {
           array_push($ret, $row[0]);
        }
        return $ret;
    }

    public function add_member($mac) {
        // add the machine specified by $mac to the database
        // also this will remove it from any other class
        $id = $this->id;
        # match against a regex
        if (($mac = validate_mac($mac)) === 0) {
            die("Error: input is not a valid MAC address");
        }
        $mac = mysql_escape_string($mac);
        mysql_query("delete from class_map where mac=\"$mac\"")
            or die("query to remove class member failed: " . mysql_error( ));
        mysql_query("insert into class_map(class_id, mac) values($id, \"$mac\")") 
            or die("query to add class member failed: " . mysql_error( ));
    }
    
    public function rename($new_name) {
        // rename the class
        $id = $this->id;
        $new_name = mysql_escape_string($new_name);
        mysql_query("update class set name=\"$new_name\" where class_id=$id")
            or die("query to rename class failed: " . mysql_error( ));
        $this->name = $new_name;
    }

    public function remove( ) {
        // remove the class (all machines will go to the default class 1)

        if ($this->id == 1) {
            die("attempt to remove the default class (class 1)!");
        }

        $id = $this->id;

        // move everyone to class 1
        mysql_query("update class_map set class_id=1 where class_id=$id")
            or die("query to move to class 1 failed: " . mysql_error( ));

        mysql_query("delete from class where class_id=$id")
            or die("query to delete class $id failed: " . mysql_error( ));
    }

    public function remove_member($mac) {
        $id = $this->id;
        if (($mac = validate_mac($mac)) === 0) {
            die("invalid MAC passed to HardwareClass::remove_member");
        }
        $mac = mysql_escape_string($mac);

        mysql_query(
            "delete from class_map where class_id=$id and mac='$mac'"
        ) or die(
            "query to delete member $mac from class $id failed:"
            . mysql_error( )
        );
    }

    public function get_id( ) {
        return $this->id;
    }
    public function get_name( ) {
        return $this->name;
    }

    public function add_override($path) {
        // Add a new configuration file override for this class.
        // This override will replace the file at the given path.
        // Any existing override will be left alone.
        $path = mysql_escape_string($path);
        $id = $this->id;

        $result = mysql_query(
            "select count(class_id) from config_override ".
            " where class_id=$id and file_path='$path'"
        ) or die(
            "failed to query for number of overrides: " . mysql_error( )
        );

        $row = mysql_fetch_row($result);
        if ($row[0] == 1) {
            return 0;
        }

        mysql_query(
            "insert into config_override (class_id, file_path)" .
            " values($id, '$path')"
        ) or die(
            "failed to insert into config_override: " . mysql_error( )
        );
        
        return 1;
    }

    public function remove_override($path) {
        $path = mysql_escape_string($path);
        $id = $this->id;
        mysql_query(
            "delete from config_override ".
            "where class_id=$id and file_path=\"$path\""
        ) or die(
            "failed to delete from config_override: " . mysql_error( )
        );
    }

    public function edit_override($path, $new_text) {
        $path = mysql_escape_string($path);
        $sig = generate_signature($new_text);
        $new_text = mysql_escape_string($new_text);
        $id = $this->id;
        mysql_query(
            "update config_override " .
            "set data=\"$new_text\", " .
            "sig=\"$sig\"" .
            "where class_id=$id and file_path='$path'"
        ) or die(
            "failed to update override for $id:$path: " . mysql_error( )
        );
    }

    public function get_override($path) {
        $path = mysql_escape_string($path);
        $id = $this->id;
        $result = mysql_query(
            "select data from config_override ".
            "where class_id=$id && file_path=\"$path\""
        ) or die(
            "failed to query override table for $id:$path: " . mysql_error( )
        );
        if ($row = mysql_fetch_row($result)) {
            return $row[0];
        }
        return 0;
    }

    public function get_override_sig($path) {
        $path = mysql_escape_string($path);
        $id = $this->id;
        $result = mysql_query(
            "select sig from config_override ".
            "where class_id=$id && file_path=\"$path\""
        ) or die(
            "failed to query override table for $id:$path: " . mysql_error( )
        );
        if ($row = mysql_fetch_row($result)) {
            return $row[0];
        }
        return 0;
    }

    public function list_overrides( ) {
        $id = $this->id;
        $result = mysql_query(
            "select file_path from config_override where class_id=$id"
        ) or die(
            "failed to query override table for $id: " . mysql_error( )
        );

        $ret = array( );

        while ($row = mysql_fetch_row($result)) {
            $ret[ ] = $row[0];
        }

        return $ret;
    }

    public function add_file($filename, $target_path) {
        $id = $this->id;
       
        # get the md5 hash of the file and sign it
        $path = BASE_DIR."/flash/".$filename;
        $md5 = md5_file($path);
        #$tmp = split('= ', exec("openssl md5 $path"));
        #$md5 = trim($tmp[1]);
        print "-->".$md5."<--";
        $sig = generate_signature($md5);
        
        $filename = basename($filename);
        if (!file_exists(BASE_DIR."/flash/$filename")) {
            die("File does not exist!");
        }
        $url = BASE_URL."/flash/$filename";

        $filename=mysql_escape_string($filename);
        $url=mysql_escape_string($url);
        $target_path=mysql_escape_string($target_path);
        $md5=mysql_escape_string($md5);
        $sig=mysql_escape_string($sig);

        mysql_query("insert into file_map (class_id, filename, url, output_path, md5, sig) " .
            "values($id, \"$filename\", \"$url\", \"$target_path\", \"$md5\", \"$sig\")") 
            or die("failure to add file to class: ". mysql_error( ));
    }

    public function remove_file($path) { 
        $path=mysql_escape_string($path);
        $id=$this->id;

        mysql_query("delete from file_map where class_id=$id and output_path=\"$path\"")
            or die("failed to delete file...");
    }

    public function list_files( ) {
        $id=$this->id;
        $result = mysql_query("select filename, url, output_path, md5, sig from file_map where class_id=$id") or die("failed to list files: " . mysql_error( ));
        $output = array();
        while ($row = mysql_fetch_row($result)) {
            $output[] = array(
                name => $row[0],
                path => $row[2],
                url => $row[1],
                md5 => $row[3],
                sig => $row[4]
            );
        }
        return $output;
    }

    private $id;
    private $name;
}

?>
