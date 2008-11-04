<script type="text/javascript"><!--
(function($) {
    $(document).ready(function() {
        $("ul#maintab").tabs();

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

        $(".feeddesc").each(function() {
              $(this).data("desc","");
           });

        $(".feedopt").mouseover(function() {
              update($(this),$(this).attr('title'));
           });

        $(".feedopt").mouseout(function() {
              update($(this),$(this).parents(".feeddiv").find(".feeddesc").data("desc"));
           });

        $(".feedsel").change(function() {
              var desc=$(this).find("option:selected").attr('title');
              $(this).parents(".feeddiv").find(".feeddesc").data("desc", desc);
              update($(this),desc);
           });

        $(".feedsel").keyup(function() {
              var desc=$(this).find("option:selected").attr('title');
              $(this).parents(".feeddiv").find(".feeddesc").data("desc", desc);
              update($(this),desc);
           });

        $("#content").keyup(function() {
              var length = $(this).val().length;
              var limit = <?= TICKER_LIMIT ?>;
              if( length > limit ) {
                  $(this).val($(this).val().substring(0, limit));
                  return false;
              }
              $(this).siblings(".content_msg").html("You have " + (limit - $(this).val().length) + " characters left.");
              return true;
           });

        update_all($("#maincontent"));

        function update_all(parent) {
           $(parent).find(".feedsel").each(function() {
                 update($(this),$(this).find("option:selected").attr('title'));
              });
        }

        function update(child, desc) {
           $(child).parents('.feeddiv').find('.feeddesc').html(desc);
        }

        $(".click_add_feed").click(function() {
            if (!confirm("You are strongly encouraged to submit to only one feed, except in extraordinary situations. Please use discretion when choosing feeds to submit to, and carefully read the help page on feeds before adding additional feeds. Note specifically that feeds and screen locations are not equivalent. Are you sure you want to continue?")) {
                return false;
            }
            var count = $(this).data("count");
            if(count == undefined)
                count = 0;
            var feeddiv = $(this).parents("tr").find(".feeddiv:last");
            var select = $(feeddiv).find(".feedsel:first");
            if(count < $(select).children().length - 2) {
               var newdiv = $(feeddiv).clone(true);
               $(newdiv).find(".feedsel:first").attr("name","content[feeds][" + ++count + "]");
               $(newdiv).find(".feeddesc").html('');
               $(newdiv).insertAfter(feeddiv);
            }

            $(this).data("count", count);
            return false;
        });
    });
})(jQuery);
//--></script>
<ul id="maintab">
	<li class="first"><a class="graphic" href="#new_image"><h1>Image</h1></a></li>
	<li class="middle"><a class="ticker" href="#new_ticker"><h1>Ticker Text</h1></a></li>
	<? if($_SESSION['user']->has_ndc_rights()){ ?>
  <li class="last"><a class="dynamic" href="#new_dynamic"><h1>NetText</h1></a></li>
  <? } ?>
</ul>
<br class="funkybreak" />
<div class="roundcont">
	<div class="roundtop"><span class="rt"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
	<div class="roundcont_main">
		<div id="new_image" class="contentstyle">
			<? include("new_image.php"); ?>
		</div>
		<div id="new_ticker" class="contentstyle">
			<? include("new_ticker.php"); ?>
		</div>
      <div id="new_dynamic" class="contentstyle">
			<?
			if($_SESSION['user']->has_ndc_rights())
			{
				include("new_dynamic.php"); 
			}
			?>
		</div>
		<div style="clear:both;"></div>
	</div>
	<div class="roundbottom"><span class="rb"><img src="<? echo ADMIN_BASE_URL ?>/images/blsp.gif" height="6" width="1" alt="" /></span></div>
</div>

