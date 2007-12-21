<? include("../includes/pageheader.php"); ?>
<link rel="stylesheet" type="text/css" href="<?php $_SERVER['DOCUMENT.ROOT'] ?>/admin_beta/ajaxtabs/ajaxtabs.css" />
<script type="text/javascript" src="<?php #_SERVER['DOCUMENT.ROOT'] ?>/admin_beta/ajaxtabs/ajaxtabs.js"></script>
<script language="javascript" type="text/javascript" src="http://signage.union.rpi.edu/admin_beta/js/datetimepicker.js"></script>
</head>
<body>
  <div id="header">
    <div id="header_padding">
      <? include("../includes/menu_tabs.php"); ?>
    </div>
  </div>
  <div id="content_header">
    <h2><a href="/">Public Interface</a> :: <a href="../index.php">Admin Home</a></h2>
    <h1>Add Content</h1>
  </div>
  <div id="maincontent">
    <h2>Select the type of screen content you would like to add.</h2>
    <div id="selectdisp_left">
      <div id="shadetabs">
        <div id="selectmenu">
          <ul id="maintab" class="shadetabs">
            <li><a href="u_file.php?ajax" rel="ajaxcontentarea">Graphic</a></li>
            <li><a href="u_text.php?ajax" rel="ajaxcontentarea">Ticker Text</a></li>
          </ul>
        </div>      
      </div>
    </div>
    <div id="selectdisp_right">
      <div id="ajaxcontentarea" class="contentstyle">
        <h1>Add New Content</h1>
        <p>Please select a content type to the left to upload something to the system.</p>
      </div>
    </div>
    <script type="text/javascript">
        //Start Ajax tabs script for UL with id="maintab" Separate multiple ids each with a comma.
        startajaxtabs("maintab")
    </script>
  </div>
  <!-- BEGIN Sidebar -->
  <? include("../includes/left_menu.php"); ?>
  <!-- END Sidebar -->
</body>
</html>
