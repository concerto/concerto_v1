<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technologies Group)
 *
 * This program is free software; you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.  You should have received a copy
 * of the GNU General Public License along with this program.
 *
 * @package      Concerto
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */
?><div style="height:220px; width:330px; float:left;">
	<img src="<?= ADMIN_BASE_URL ?>images/graphic_icon.jpg" alt="" />
</div>
<h1 class="addcontent">Add Image</h1>
<h2>Fill in these details to post a new graphical flyer to Concerto.</h2>
<h2>Please review the <a TARGET="_blank" href="<?= ADMIN_URL ?>/pages/show/docs/1">Style Guide for Graphical Content</a> before submitting graphics to Concerto.</h2>
<div style="clear:both;"></div>
<form enctype="multipart/form-data" method="post" action="<?=ADMIN_URL?>/content/create">
<br /><br /><table class='edit_win' style="margin-top:-18px" cellpadding='6' cellspacing='0'>
  <tr>
  <td><h5>File to Upload</h5><p><b>Specify the file you would like to upload to Concerto.</b></p></td>
  <td class="edit_col">
    <input name="content_file" class="extended" type="file" />
    <br /><br />
    <p>Accepted file types: JPEG, PNG, GIF, PDF, Powerpoint*<br /> * <i>Experimental; please upload one slide at a time</i></p>
    <input name="content[upload_type]" value="file" type="hidden" />
  </td>
  </tr>
</table>
<br />
<?php
   include("_form.php");
?>
<input value="Submit Content" type="submit" name="submit" />
</form>
