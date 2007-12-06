<? include_once("/var/www/ds/upload/upload.php"); ?>
<h1>Add Graphic Content</h1>
<table cellpadding="0" cellspacing="18">
  <tr valign="middle">
    <td><img src="<?php echo $admin_url ?>/images/graphic_icon.jpg" alt="" /></td>
    <td>
      <p>Graphic content is highly visual in nature and can be in the form of JPEG, PNG, or GIF images, as well as Powerpoint slides</p>
    </td>
  </tr>
</table>
<?php
	if(isset($_POST['Submit'])){
		echo uploadProcess();
	} else {
		echo uploadForm();
	}

?>
