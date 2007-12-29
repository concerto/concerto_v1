<? include_once("/var/www/ds/upload/upload.php"); ?>
<h1>Add Ticker Text Content</h1>
<table cellpadding="0" cellspacing="18">
  <tr valign="middle">
    <td><img src="<?php $_SERVER['DOCUMENT.ROOT'] ?>/admin_beta/images/ticker_icon.jpg" alt="" /></td>
    <td>
      <p>You can add general announcements in text form to a rotating ticker that appears on 
      the signage displays.  Use this space for announcements and reminders that aren't 
      date- or event-bound and don't require any graphics.</p>
    </td>
  </tr>
</table>
<?php
	if(isset($_POST['Submit'])){
	echo tuploadProcess();
	} else {
	echo tuploadForm();
	}

?>
