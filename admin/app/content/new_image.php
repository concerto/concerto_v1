<p><a href="<?echo ADMIN_URL ?>/users">Back to Users Listing</a></p>
<h3>Use this form to add your image content.<h3>

<form enctype="multipart/form-data" method="POST" action="<?=ADMIN_URL?>/content/create">
<?php 
   include("_form.php");
?>
<table class='edit_win' style="margin-top:-18px"; cellpadding='6' cellspacing='0'>
  <tr>
  <td><h5>File to Upload</h5></td>
  <td class="edit_col">
    <input name="content_file" id="content_file" type="file" />
    <input name="content[upload_type]" value="file"
    id="content_upload_type" type="hidden" />
  </td>
</table>
<br />
<input value="Submit Content" type="submit" name="submit" />
</form>