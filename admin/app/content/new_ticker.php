<h1>Add Ticker Text</h1>
<h2>Fill in these details to post a text announcement to Concerto.</h2>
<img src="<?= ADMIN_BASE_URL ?>/images/ticker_icon.jpg" alt="" />
<br />
<form method="post" action="<?=ADMIN_URL?>/content/create">
<br /><br /><table class='edit_win' style="margin-top:-18px" cellpadding='6' cellspacing='0'>
  <tr>
  <td><h5>Ticker Text</h5><p><b>Enter the text announcement or message that will be displayed.</b></p></td>
  <td class="edit_col">
    <textarea name="content[content]" id="content" rows="3" cols="40"></textarea>
    <input name="content[upload_type]" value="text" type="hidden" />
    <p id="content_count" class="content_msg">Please limit your ticker text to <?= TICKER_LIMIT ?> characters.</p>
  </td>
  </tr>
</table>
<br />
<?php 
   include("_form.php");
?>
<input value="Submit Content" type="submit" name="submit" />
</form>
