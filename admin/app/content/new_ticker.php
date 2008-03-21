<p><a href="<?echo ADMIN_URL ?>/users">Back to Users Listing</a></p>
<h3>Use this form to add your image content.<h3>

<form method="POST" action="<?=ADMIN_URL?>/content/create">
<?php 
   include("_form.php");
?>
<table class='edit_win' style="margin-top:-18px"; cellpadding='6' cellspacing='0'>
  <tr>
  <td><h5>Ticker Text</h5></td>
  <td class="edit_col">
    <input name="content_data" id="content" type="text" size="40" />
    <input name="content[content]" value="text"
    id="content_upload_type" type="hidden" />
  </td>
</table>
<br />
<input value="Submit Content" type="submit" name="submit" />
</form>
