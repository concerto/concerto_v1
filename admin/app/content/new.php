<div id="selectdisp_left">
  <div id="shadetabs">
    <div id="selectmenu">
      <ul id="maintab" class="shadetabs">
        <li><a href="<?= ADMIN_URL ?>/content/new_image?ajax" rel="ajaxcontentarea">Image</a></li>
        <li><a href="<?= ADMIN_URL ?>/content/new_ticker?ajax" rel="ajaxcontentarea">Ticker Text</a></li>
      </ul>
    </div>      
  </div>
</div>
<div id="selectdisp_right">
  <div id="ajaxcontentarea" class="contentstyle">
    <h1>Add New Content</h1>
    <p>Choose a content type to the left to begin.</p>
  </div>
</div>
<script type="text/javascript">
//Start Ajax tabs script for UL with id="maintab" Separate multiple ids each with a comma.
startajaxtabs("maintab")
</script>
