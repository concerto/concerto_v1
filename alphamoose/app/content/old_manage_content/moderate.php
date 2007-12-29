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
    <h1>Awaiting Moderation (2 New)</h1>
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
    <table class="edit_win" cellpadding="6" cellspacing="0">
	   <tr>
        <td><a href="index.php?edit_id=24" ><img src="http://signage.union.rpi.edu/upload/minimage.php?source=/var/www/ds/content/24.jpg&scale=0.25&type=1"  alt="" /></a></td>
        <td class="edit_col">
          <h1><a href="index.php?edit_id=24" >RPI TV ad</a></h1>
          <span style="font-size:1.5em;font-weight:bold;color:#333;margin-bottom:12px;">04/29/2007 - 05/11/2008</span>
          <h2>Submitted by <strong>emalac</strong></h2>
          <span style="color:green;font-size:1.2em;font-weight:bold;"><a style="color:green; text-decoration:none;" href="#">Approve</a></span> | <span style="color:red;font-size:1.2em;font-weight:bold;"><a style="color:red; text-decoration:none;" href="#">Deny</a></span><br />
        </td>
      </tr>
      <tr>
        <td><a href="index.php?edit_id=56" ><img src="http://signage.union.rpi.edu/upload/minimage.php?source=/var/www/ds/content/56.jpg&scale=0.25&type=1"  alt="" /></a></td>
        <td class="edit_col">
          <h1><a href="index.php?edit_id=56" >Capoeira Slide 1</a></h1>
          <span style="font-size:1.5em;font-weight:bold;color:#333;margin-bottom:12px;">10/15/2007 - 10/22/2007</span>
          <h2>Submitted by <strong>regular</strong></h2>
          <span style="color:green;font-size:1.2em;font-weight:bold;"><a style="color:green; text-decoration:none;" href="#">Approve</a></span> | <span style="color:red;font-size:1.2em;font-weight:bold;"><a style="color:red; text-decoration:none;" href="#">Deny</a></span><br />
        </td>
      </tr>
    </table>
  </div>
  <!-- BEGIN Sidebar -->
  <? include("../includes/left_menu.php"); ?>
  <!-- END Sidebar -->
</body>
</html>
