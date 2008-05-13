<?php
class moderateController extends Controller
{
    public $actionNames = Array( 'feed'=> 'Feeds Moderation',
                                 'approve' => 'Approve Content',
                                 'deny' => 'Deny Content');

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
        if($feed && $action="approve"){
            #echo json_encode($feed->content_mod($content_id, 1));
            echo "true";
        } elseif($feed && $action="deny") {
            #echo json_encode($feed->content_mod($content_id, 0));
            echo "true";
        } else {
            echo json_encode(false);
        }
    }

    function approveAction()
    {
        $feed = new Feed($this->args[1]);
        if(!$feed){
            $this->flash('Feed not found', 'error');
            redirect_to(ADMIN_URL."/moderate");
        }

        if(!$feed->user_priv($_SESSION['user'], 'moderate')){
            $this->flash('You do not have enough privileges to moderate this feed', 'error');
            redirect_to(ADMIN_URL."/moderate");
        }

        $cid = $this->args[2];

        if($feed->content_mod($cid, 1)) {
            $this->flash('Content approved successfully.');
            redirect_to(ADMIN_URL.'/moderate/feed/'.$feed->id);
        } else {
            $this->flash('Content approval failed.','error');
            redirect_to(ADMIN_URL.'/moderate/feed/'.$feed->id);
        }
    }

    function denyAction()
    {
        $feed = new Feed($this->args[1]);
        if(!$feed){
            $this->flash('Feed not found', 'error');
            redirect_to(ADMIN_URL."/moderate");
        }

        if(!$feed->user_priv($_SESSION['user'], 'moderate')){
            $this->flash('You do not have enough privileges to moderate this feed', 'error');
            redirect_to(ADMIN_URL."/moderate");
        }

        $cid = $this->args[2];

        if($feed->content_mod($cid, 0)) {
            $this->flash('Content denied successfully.');
            redirect_to(ADMIN_URL.'/moderate/feed/'.$feed->id);
        } else {
            $this->flash('Content denial failed.','error');
            redirect_to(ADMIN_URL.'/moderate/feed/'.$feed->id);
        }
    }
}
?>
