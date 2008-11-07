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
        $this->feeds['public_feeds'] = Feed::get_all("WHERE type = 0 ORDER BY name");
        $this->feeds['restricted_feeds'] = Feed::get_all("WHERE type = 2 OR type = 1 OR type = 4 ORDER BY name");
        
        if($_SESSION['user']->admin_privileges){
            $this->feeds['private_feeds'] = Feed::get_all("WHERE type = 3  ORDER BY name");
        } else {
            $group_str = implode(',',$_SESSION['user']->groups);
            $this->feeds['private_feeds'] = Feed::get_all("WHERE type = 3 AND group_id IN ($group_str) ORDER BY name");
        }
        
        if(!is_array($this->feeds['public_feeds'])){
          $this->feeds['public_feeds'] = array();
        }
        if(!is_array($this->feeds['restricted_feeds'])){
          $this->feeds['restricted_feeds'] = array();
        }
        if(!is_array($this->feeds['private_feeds'])){
          $this->feeds['private_feeds'] = array();
        }
    }

    function showAction()
    {
        if(!isset($this->args[1]) && !is_numeric($this->args[1]))
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

        $this->type_id = escape($this->args[3]);

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

        $this->setTitle("{$this->feed->name} - {$this->type_name}");
        $this->setSubject("{$this->feed->name} - {$this->type_name}");
        $this->breadcrumb($this->feed->name, "browse/show/".$this->feed->id);
        $this->breadcrumb($this->type_name);
    }

    function feedAction()
    {
        $this->feed = new Feed($this->args[1]);
        if(!$this->feed) {
           $this->flash('Feed not found', 'error');
           redirect_to(ADMIN_URL."/feeds");
        }                                                                  

        $this->group = new Group($this->feed->group_id);                   

        $sql = "SELECT COUNT(content.id) FROM feed_content
                LEFT JOIN content ON feed_content.content_id = content.id
                WHERE feed_content.feed_id = {$this->feed->id}
                AND moderation_flag = 1
                AND content.end_time > NOW()
                GROUP BY feed_content.feed_id;";
        $this->active_content = sql_query1($sql);
        if($this->active_content < 0)
           $this->active_content = 0;
        
        $sql = "SELECT COUNT(content.id) FROM feed_content  
                LEFT JOIN content ON feed_content.content_id = content.id
                WHERE feed_content.feed_id = {$this->feed->id}
                AND moderation_flag = 1
                AND content.end_time < NOW()
                GROUP BY feed_content.feed_id;";
        $this->expired_content = sql_query1($sql);
        if($this->expired_content < 0)
           $this->expired_content = 0;

        $sql = "SELECT COUNT(content.id) FROM content
                LEFT JOIN feed_content
                ON content.id = feed_content.content_id
                WHERE feed_content.feed_id = {$this->feed->id}
                AND feed_content.moderation_flag IS NULL
                GROUP BY feed_content.feed_id
                ORDER BY content.type_id, content.name;";
        $this->waiting = sql_query1($sql);

        $this->setTitle($this->feed->name);
        $this->setSubject($this->feed->name);
    }
   
    function detailsAction()
    {
        $this->content = new Content($_POST['content_id']);
        $this->feed = new Feed($_POST['feed_id']);
        if($this->content == false || $this->feed == false) exit();
        $this->duration = $this->content->get_duration($this->feed);
        $this->status = $this->content->get_moderation_status($this->feed);
        $this->week_range = date('W',strtotime($this->content->end_time)) - date('W',strtotime($this->content->start_time));
        $this->submitter = new User($this->content->user_id);
        if($this->feed->user_priv($_SESSION['user'], 'moderate')) {
            $this->moderator = $this->content->get_moderator($this->feed);
        }
        if($this->duration >= 24*60*60*1000){
          $math = round($this->duration / (24*60*60*1000));
          $unit = 'day';
        }else if($this->duration >= 60*60*1000){
          $math = round($this->duration / (60*60*1000));
          $unit = 'hour';
        }else if($this->duration >= 60*1000){
          $math = round($this->duration / (60*1000));
          $unit = 'minute';
        }else if($this->duration >= 1000){
          $math = round($this->duration / (1000));
          $unit = 'second';
        }
        if(isset($math)){
            $this->dur_str = $math . ' ' . $unit;
            $this->dur_str .= $math > 1 ? "s" : "";
        } else {
            $this->dur_str = 'Unknown';
        }
    }
}
?>
