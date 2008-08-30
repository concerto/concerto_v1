<script type="text/javascript"><!--
(function($) {
    $(document).ready(function() {
        $("#selectmenu > ul").tabs();

        $.datepicker.setDefaults({showOn: 'both',
                                  buttonImageOnly: true,
                                  buttonImage: '<?= ADMIN_BASE_URL ?>images/cal_icon.gif',
                                  buttonText: 'Calendar',
                                  showAnum: "fadeIn"});

        $(".start_date").datepicker();

        $(".end_date").datepicker();

        $(".click_start_time").click(function() {
            $(this).hide().parents("table")
                .find(".start_time_select").show()
                .parents("table")
                .find(".start_time_msg").hide();
            return false;
        });

        $(".click_end_time").click(function() {
            $(this).hide().parents("table")
                .find(".end_time_select").show()
                .parents("table")
                .find(".end_time_msg").hide();
            return false;
        });

        $(".click_duration").click(function() {
            $(this).hide().parents("table")
                .find(".duration_div").show()
                .parents("table")
                .find(".duration_msg").hide();
            return false;
        });

        $(".click_add_feed").click(function() {
            var count = $(this).data("count");
            if(count == undefined)
                count = 0;
            var select = $(this).parents("tr").find("select:last");
            if(count < $(select).children().length - 2)
                $(select).clone().attr("name", "content[feeds][" + ++count + "]").insertAfter(select);
            $(this).data("count", count);
            return false;
        });
    });
})(jQuery);
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
	
	<div class="roundcont">
		<div class="roundtop"><span class="rt"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
		<div class="roundcont_main">
			<div id="new_image" class="contentstyle">
				<? include("new_image.php"); ?>
			</div>
			<div id="new_ticker" class="contentstyle">
				<? include("new_ticker.php"); ?>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div class="roundbottom"><span class="rb"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
	</div>
	
</div>