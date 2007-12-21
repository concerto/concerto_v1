<? include("../includes/pageheader.php"); ?>
<? 
require_once("/var/www/ds/util.php");
require_once("/var/www/ds/upload/preview.php");
?>

</head>

<body>
  <div id="header">
    <div id="header_padding">
      <? include("../includes/menu_tabs.php"); ?>
    </div>
  </div>
  <div id="content_header">
    <h2><a href="/">Public Interface</a> :: <a href="../index.php">Admin Home</a></h2>
    <h1>Archived Content</h1>
  </div>
  <div id="maincontent">
    <!-- feed + field select box mock-up inserted by BrZ -->
    <div class="select">
      <div class="select_padding">
        <table cellpadding="0" cellspacing="0">
          <tr>
            <td><h3>Select content type:</h3></td>
            <td>
              <select>
                <option>Graphic</option>
                <option>Ticker Text</option>
              </select>
            </td>
            <td><h3>Select feed:</h3></td>
            <td>
              <select>
                <option>General</option>
                <option>RPI TV</option>
                <option>Lally School</option>
              </select>
            </td>
            <td><button>Show</button></td>
          </tr>
        </table>
      </div>
    </div>
<?php
/*
 * STATUS: Working (I think)
 * Brian:
 * This code will deal with administering content, listing content, 
 * basic "approving" of content, modifying content meta-data, deleting, etc
 */


function list_content($stype, $id = 0){
	if($stype == "all"){
		//Display all the content in the DB
		$query = "SELECT content.id, 	
		title,user.username,created,start_date,end_date,content,is_live, 		
		feed.name AS fname, data.name AS dname
		FROM content
		INNER JOIN feed ON feed.id = feed_id
		INNER JOIN user ON user.id = user_id
		INNER JOIN data ON data.id = data_type
		ORDER BY id ASC";
		
	} else {
		//Based on $stype filter
		$query = "SELECT content.id, 	
		title,user.username,created,start_date,end_date,content,is_live, 		
		feed.name AS fname, data.name AS dname
		FROM content
		INNER JOIN feed ON feed.id = feed_id
		INNER JOIN user ON user.id = user_id
		INNER JOIN data ON data.id = data_type
		WHERE " . $stype . " = " . $id;
	}
	$res = mysql_query($query) or die("Problem finding content.");
	echo '<table class="edit_win" cellpadding="6" cellspacing="0">';
	while ($row = mysql_fetch_assoc($res)){
     if($row['is_live']){
       $stat = $_SERVER['DOCUMENT.ROOT'] . "/admin_beta/images/sign_live.gif";  // for live case
     } else{
       $stat = $_SERVER['DOCUMENT.ROOT'] . "/admin_beta/images/sign_notlive.gif";  // for Not Live case
     }
     echo '<tr>';
     echo "<td><a href=\"index.php?edit_id=" . $row['id'] . "\" >" . preview($row['id'] , "../upload/") . "</a></td>";
     echo '<td class="edit_col">' .
       "<h2><img src='$stat' style='border:0px;' border='0' alt='' /></h2>" .
       "<h1><a href=\"index.php?edit_id=" . $row['id'] . "\" >" . $row['title'] . '</a></h1>' .
       '<span style="font-size:1.5em;font-weight:bold;color:#333;margin-bottom:12px;">'.date("m/j/Y",strtotime($row['start_date'])).' - '.date("m/j/Y",strtotime($row['end_date'])).'</span>' .
       '<h2>Submitted by <strong>' . $row['username'] . '</strong></h2>';
     
     if(isset($_REQUEST['display_full_details']))
       {
         echo '<table class="item_details" cellpadding="0" cellspacing="0" width="60%">'
           . '<tr><td width="50%"><strong>Created</strong></td><td width="50%">'. $row['created'] .'</td></tr>'
           . '<tr><td><strong>Status</strong></td><td>' . $stat . '</td></tr>'
           . '<tr><td><strong>Start Date</strong></td><td>' . $row['start_date'] . '</td></tr>'
           . '<tr><td><strong>Start Date</strong></td><td>' . $row['end_date'] . '</td></tr>'
           . '<tr><td><strong>Feed ID</strong></td><td>' . $row['fname'] . '</td></tr>'
           . '<tr><td><strong>Ad Type</strong></td><td>' . $row['dname'] . '</td></tr>'
           . '<tr><td><strong>Filename</strong></td><td>' . $row['content'] . '</td></tr></table>';
       }
   }
   echo '</table><br /><br />';
}

function edit_content($id) {
//Provides edit ability for content

	$query = "SELECT content.id, content_type, title, content, user.username, created, start_date, 		
		end_date, is_live, data_type, feed_id
		FROM content
		INNER JOIN user ON user.id = user_id
		WHERE content.id = '$id'";
	$res = mysql_query($query);
	$row = mysql_fetch_assoc($res);
	echo preview($id , "../upload/", .50) . '<br /><br />';
	//print_r($row);
	
	//This code is similiar to the code used for the upload form with default values
	
	$cal = "<script language=\"javascript\" type=\"text/javascript\" src=\"http://signage.union.rpi.edu/admin_beta/js/datetimepicker.js\" ></script>";
	//$cal = "";
	
	echo 
	 $cal . startUploadForm("Edit") . 
	"<table class='edit_win' cellpadding='6' cellspacing='0'><tr><td class='firstrow'>" . 
	"<h5>Content Title</h5></td><td class='edit_col firstrow'>" . textBox("title", $row['title']) . "</td></tr><tr><td>" . 
	"<h5>Start Date</h5></td><td>" . textBox("start_date", $row['start_date']) . "&nbsp;&nbsp;" .
	"<a href = \"javascript:NewCal('start_date','ddmmmyyyy',true,24)\"><img alt=\"Pick a date\" src=\"../images/cal.gif\" border=\"0\" width=\"16\" height=\"16\"></a> " . "</td></tr><tr><td>" . 
	"<h5>End Date</h5></td><td>" . textBox("end_date", $row['end_date']) . "&nbsp;&nbsp;" .
	"<a href = \"javascript:NewCal('end_date','ddmmmyyyy',true,24)\"><img alt=\"Pick a date\" src=\"../images/cal.gif\" border=\"0\" width=\"16\" height=\"16\"></a>" . "</td></tr><tr><td>" .
	"<h5>Content Type</h5></td><td>" . listDatas("data_id", $row['data_type']) . "</td></tr><tr><td>" . 
	"<h5>Feed ID</h5></td><td>" . listFeeds("feed_id", $row['feed_id']) . "</td></tr><tr><td>" .
	"<h5>Content ID</h5></td><td>" . $row['id'] . "</td></tr><tr><td>" .
	"<h5>Content Path</h5></td><td>" . $row['content'] . "</td></tr><tr><td>" .
	"<h5>Created</h5></td><td>" . cleantime($row['created']) . "</td></tr><tr><td>" .
	"<h5>Duration</h5></td><td>" . duration($row['start_date'], $row['end_date']) . "</td></tr><tr><td>" .
	"<h5>User</h5></td><td>" . $row['username'] . "</td></tr><tr><td>" .
	"<h5>Moderation</h5></td><td>" . live_list("is_live",$row['is_live']) . "</td></tr><tr><td>" .
	hidden(id, $row['id']) . "</td><td>" . submit("Submit") . "&nbsp;&nbsp;" . submit("Delete") . "</td></tr></table>" . endForm() . "<br /><br />";
}

function process_edit_content(){
	$id = escape($_POST['id']);
	$title = escape($_POST['title']);
	$start_date = date("Y-m-d H:i:s", strtotime($_POST['start_date']));
	$end_date = date("Y-m-d H:i:s", strtotime($_POST['end_date']));
	$feed_id = escape($_POST['feed_id']);
	$data_type=escape($_POST['data_id']);
	$is_live = escape($_POST['is_live']);
	
	$query = "UPDATE content SET title = '$title', start_date = '$start_date', end_date = '$end_date',			
			 feed_id = '$feed_id', data_type = '$data_type', is_live = '$is_live' WHERE id = '$id'";
	
	//echo $query;
	mysql_query($query) or die ("Error updating content db");
	
	echo "Content Updated";

}

function delete_content($id){
//Maybe at some point we never want to delete anything for record purposes, until then this
//function will do it

	$query = "SELECT * FROM content WHERE id = '$id'";
	$res = mysql_query($query);
	$row = mysql_fetch_assoc($res);

	if($row['content_type'] != 0){ //implies image
		$target_path = CONTENT_PATH . $row['content'];
		unlink($target_path);
	}
	$query = "DELETE FROM content WHERE id = '$id'";
	mysql_query($query);

	$query = "DELETE FROM content_feed WHERE content_id = '$id'";
	mysql_query($query);
	 return "Content removed";
 }
 
function approve_content($id, $approval=1){
	//If you want to unapprove content, pass a 0 for $approval
	$query = "UPDATE content SET is_live = '$approval' WHERE id = '$id'";
	mysql_query($query);
}
 
if(isset($_POST['Submit'])){
		echo process_edit_content();
}
if(isset($_POST['Delete'])){
		echo delete_content($_POST['id']);
}
if(isset($_GET['edit_id'])){
		echo edit_content($_GET['edit_id']);
} else {
	echo "<h2>Click on an image or item name to view details for that entry.</h2>";
	list_content("all");
}
	
	
	?>

  </div>
  <!-- BEGIN Sidebar -->
  <? include("../includes/left_menu.php"); ?>
  <!-- END Sidebar -->
</body>
</html>
