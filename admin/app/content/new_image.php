<h1>Add Image</h1>
<h2>Fill in these details to post a new image to Concerto.</h2>
<img src="/admin/images/graphic_icon.jpg" alt="" />
<br />
<form enctype="multipart/form-data" method="POST" action="<?=ADMIN_URL?>/content/create">
<?php 
   include("_form.php");
?>
<table class='edit_win' style="margin-top:-18px"; cellpadding='6' cellspacing='0'>
  <tr>
  <td><h5>File to Upload</h5></td>
  <td class="edit_col">
    <input name="content_file" class="extended" id="content_file" type="file" />
    <input name="content[upload_type]" value="file" id="content_upload_type" type="hidden" />
  </td>
</table>
<br />
<input value="Submit Content" type="submit" name="submit" />
</form>
