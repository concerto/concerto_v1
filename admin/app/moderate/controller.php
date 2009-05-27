<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technolgies Group)
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
class moderateController extends Controller
{
    public $actionNames = Array( 'feed'=> 'Feeds Moderation',
                                 'confirm' => 'Confirm Content' );

    public $require = Array( 'require_login'=>1 );

    function setup()
    {
        $this->setName("Moderate");
        $this->setTemplate('blank_layout', Array('post'));
    }

    function indexAction()
    {
        $sql = "SELECT feed_content.feed_id as feed_id, COUNT(content.id) as cnt
                FROM feed_content
                LEFT JOIN content ON feed_content.content_id = content.id
                WHERE feed_content.moderation_flag IS NULL
                GROUP BY feed_content.feed_id;";
        $res = sql_query($sql);

        for($i = 0;$row = sql_row_keyed($res,$i);++$i){
            $this->count[$row['feed_id']] = $row['cnt'];
            $new_feed = new Feed($row['feed_id']);
            if($new_feed->user_priv($_SESSION['user'], 'moderate'))
                $this->feeds[] = $new_feed;
        }

        $this->setTitle('Moderation');
        $this->setSubject('Moderate');
    }

    function feedAction()
    {
        $this->feed = new Feed($this->args[1]);
        if(!$this->feed->user_priv($_SESSION['user'], 'moderate')){
            $this->flash('You do not have enough privileges to moderate this feed', 'error');
            redirect_to(ADMIN_URL."/moderate");
        }

        $sql = "SELECT content.id FROM content
                LEFT JOIN feed_content
                ON content.id = feed_content.content_id
                WHERE feed_content.feed_id = {$this->feed->id}
                AND feed_content.moderation_flag IS NULL
                GROUP BY content.id
                ORDER BY content.type_id, content.name;";
        $res = sql_query($sql);
        for($i = 0; $row = sql_row_keyed($res, $i); ++$i){
            $this->contents[] = new Content($row['id']);
        }

        $this->setTitle('Moderating '.$this->feed->name);
        $this->breadcrumb($this->feed->name);
    }

    function postAction()
    {
        $feed = new Feed($_POST['feed_id']);
        $content_id = $_POST['content_id'];
        $action = $_POST['action'];
        if($action == "approve") {
            if(!is_numeric($_POST['duration'])) {
                $this->flash('Please enter a valid duration', 'error');
                if($_POST['ajax']) {
                    echo "false";
                    return;
                } else {
                    redirect_to(ADMIN_URL.'/moderate/confirm/'.$action.'?feed_id='.$feed->id.'&content_id='.$content_id);
                }
            }
            $duration = $_POST['duration'] * 1000;
        }
        $notification = $_POST['notification'];
        if($feed && $action=="approve"){
            $return_code = $feed->content_mod($content_id, 1, $_SESSION['user'], $duration, $notification);
        } elseif($feed && $action=="deny") {
            if($_POST['information'])
                $notification = $_POST['information'] . "  " . $notification;
            $return_code = $feed->content_mod($content_id, 0, $_SESSION['user'], $duration, $notification);
        } else {
            $return_code = false;
        }
        if($_POST['ajax']) {
            echo json_encode($return_code);
        } else {
            if($return_code) {
                if($action == "approve")
                    $this->flash('Content approved successfully.');
                else
                    $this->flash('Content denied successfully.');                
            } else {
                if($action == "approve")
                    $this->flash('Content approval failed.','error');
                else
                    $this->flash('Content denial failed.','error');                
            }
            redirect_to(ADMIN_URL.'/moderate/feed/'.$feed->id);            
        }
    }
    
    function confirmAction()
    {
        $this->feed = new Feed($this->args[2]);
        if(!$this->feed){
            $this->flash('Feed not found', 'error');
            redirect_to(ADMIN_URL."/moderate");
        }

        if(!$this->feed->user_priv($_SESSION['user'], 'moderate')){
            $this->flash('You do not have enough privileges to moderate this feed', 'error');
            redirect_to(ADMIN_URL."/moderate");
        }

        if(!$this->args[1] == "approve" || !$this->args[1] == "deny"){
            $this->flash('You did not select an action for the moderation process', 'error');
            redirect_to(ADMIN_URL."/moderate");
        }

        if($this->args[4] == "ajax")
            $this->template="blank_layout.php";
        $this->content = new Content($this->args[3]);
    }
}
?>
