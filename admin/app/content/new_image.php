<h1>Add Image</h1>
<h2>Fill in these details to post a new graphical image to Concerto.  It could be contained within an image file (JPEG, PNG) or a PDF.</h2>
<img src="<?= ADMIN_BASE_URL ?>/images/graphic_icon.jpg" alt="" />
<br />
<form enctype="multipart/form-data" method="POST" action="<?=ADMIN_URL?>/content/create">
<br /><br /><table class='edit_win' style="margin-top:-18px"; cellpadding='6' cellspacing='0'>
  <tr>
  <td><h5>File to Upload</h5><p><b>Specify the file you would like to upload to Concerto.</b></p></td>
  <td class="edit_col">
    <input name="content_file" class="extended" id="content_file" type="file" />
    <br /><br />
    <p>Accepted file types: JPEG, PNG, GIF, PDF</p>
    <input name="content[upload_type]" value="file" id="content_upload_type" type="hidden" />
  </td>
</table>
<br />
<?php 
   include("_form.php");
?>
<input value="Submit Content" type="submit" name="submit" />
</form>
