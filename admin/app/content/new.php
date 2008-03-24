<script type="text/javascript"><!--
$(function() {
    $("#selectmenu > ul").tabs();
    $.datepicker.setDefaults({showOn: 'both', buttonImageOnly: true, buttonImage: '<?= ADMIN_BASE_URL ?>images/cal_icon.gif', buttonText: 'Calendar'});
    $(".start_date").datepicker({showAnim: "fadeIn"});
    $(".end_date").datepicker({showAnim: "fadeIn"});
});
//--></script>
<div id="selectdisp_left">
  <div id="shadetabs">
    <div id="selectmenu">
      <ul id="maintab" class="shadetabs">
        <li><a href="#new_image">Image</a></li>
        <li><a href="#new_ticker">Ticker Text</a></li>
      </ul>
    </div>      
  </div>
</div>
<div id="selectdisp_right">
  <div id="new_image" class="contentstyle">
    <? include("new_image.php"); ?>
  </div>
  <div id="new_ticker" class="contentstyle">
    <? include("new_ticker.php"); ?>
  </div>
</div>
