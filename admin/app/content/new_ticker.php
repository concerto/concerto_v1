<h1>Add Ticker Text</h1>
<h2>Fill in these details to post a text announcement to Concerto.</h2>
<img src="/admin/images/ticker_icon.jpg" alt="" />
<br />
<form method="POST" action="<?=ADMIN_URL?>/content/create">
<?php 
   include("_form.php");
?>
<table class='edit_win' style="margin-top:-18px"; cellpadding='6' cellspacing='0'>
  <tr>
  <td><h5>Ticker Text</h5></td>
  <td class="edit_col">
    <input name="content[content]" id="content" class="extended" type="text" size="40" />
    <input name="content[upload_type]" value="text" id="content_upload_type" type="hidden" />
  </td>
</table>
<br />
<input value="Submit Content" type="submit" name="submit" />
</form>
