<? include("../includes/pageheader.php"); ?>
</head>
<body>
  <div id="header">
    <div id="header_padding">
      <? include("../includes/menu_tabs.php"); ?>
    </div>
  </div>
  <div id="content_header">
    <h1>Access Denied</h1>
  </div>
  <div id="maincontent">
    <p>You do not have access to the requested resource.</p>
<?php if(loggedIn()) ?>
    <p>You may only view and modify items according to your permissions level.  If you need access to this resource, contact, <a href="mailto:signage@union.rpi.edu">signage@union.rpi.edu</a></p>
<?php else ?>
    <p>To obtain an account on Digital Signage, please contact <a href="mailto:signage@union.rpi.edu">signage@union.rpi.edu</a></p>
    
  </div>
  <!-- BEGIN Sidebar -->
  <? include("../includes/left_menu.php"); ?>
  <!-- END Sidebar -->
</body>
</html>
