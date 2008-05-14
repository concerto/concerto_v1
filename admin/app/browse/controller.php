<?php
class browseController extends Controller
{
    public $actionNames = Array( 'list'=>'Browse Feeds', 'feed'=>'Feed Listings' );
    public $require = Array( 'require_login'=>1 );

    function setup()
    {
        $this->setName("Browse");
        $this->setTemplate('blank_layout', Array('details'));
    }

    function indexAction()
    {
        $this->listAction();
        $this->renderView("browse", "list");
    }

    function listAction()
    {
        $this->feeds=Feed::get_all();
    }

    function showAction()
    {
        if(!isset($this->args[1]))
            redirect_to(ADMIN_URL."/browse");
        $this->feed = new Feed($this->args[1]);
        if(!isset($this->feed->id)) {
            $this->flash('Feed not found.', 'error');
            redirect_to(ADMIN_URL.'/browse');
        }
        if(!$this->feed->user_priv($_SESSION['user'])) {
            $this->flash('You do not have access to this feed.', 'error');
            redirect_to(ADMIN_URL.'/browse');
        }
        $this->group = new Group($this->feed->group_id);

        if($this->args[2] != "type") {
            $this->feedAction();
            $this->renderView("browse", "feed");
            return;
        }

        $this->type_id = $this->args[3];

        if($this->args[4] == "expired") {
            $where = "content.end_time < NOW() AND feed_content.moderation_flag = 1";
        } elseif($this->args[4] == "declined") {
            if($this->feed->user_priv($_SESSION['user'], "moderate")) {
                $where = "feed_content.moderation_flag = 0";
            } else {
                $this->flash('You do nt have access to view declined content on this feed', 'error');
                redirect_to(ADMIN_URL."/browse/show/{$this->feed->id}/type/{$this->type_id}");
            }
        } else {
            $where = "content.end_time > NOW() AND feed_content.moderation_flag = 1";
        }

        $this->contents = $this->feed->content_get_by_type($this->type_id, $where);

        $sql = "SELECT name FROM type WHERE id = {$this->type_id} LIMIT 1;";
        $this->type_name = sql_query1($sql);

        $sql = "SELECT COUNT(content.id) FROM content
                LEFT JOIN feed_content
                ON content.id = feed_content.content_id
                WHERE feed_content.feed_id = {$this->feed->id}
                AND feed_content.moderation_flag IS NULL
                GROUP BY feed_content.feed_id
                ORDER BY content.type_id, content.name;";
        $this->waiting = sql_query1($sql);

        $this->setTitle("{$this->feed->name} - {$this->type_name}");
        $this->setSubject("{$this->feed->name} - {$this->type_name}");
        $this->breadcrumb($this->feed->name, "browse/show/".$this->feed->id);
        $this->breadcrumb($this->type_name."!");
    }

    function feedAction()
    {
        $this->feed = new Feed($this->args[1]);
        $this->setTitle($this->feed->name);
        $this->setSubject($this->feed->name);
    }
   
    function detailsAction()
    {
        $this->content = new Content($_POST['content_id']);
        $this->feed = new Feed($_POST['feed_id']);
        if($this->content == false || $this->feed == false) exit();
        $this->duration = $this->content->get_duration($this->feed);
        $this->week_range = date('W',strtotime($this->content->end_time)) - date('W',strtotime($this->content->start_time));
        $this->submitter = new User($this->content->user_id);
        $this->moderator = $this->content->get_moderator($this->feed);
    }
}
?>
