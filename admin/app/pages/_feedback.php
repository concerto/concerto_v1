<br>
<script type="text/javascript">
$(document).ready(function() {

  //Text entry out of way until click on a radio button
  $('#feedback_msg').hide();

  //Clear buttons in case 
  $('input[name=helpful]').attr('checked',false);

  //Check yes button when yes label clicked
  $('#feedback_yes_lbl').click(function() {
    $('#feedback_yes').attr('checked',true);
  });

  //Check no button when no label clicked
  $('#feedback_no_lbl').click(function() {
    $('#feedback_no').attr('checked',true);
  });

  //Reveal feedback and submit after a click
  $('#feedback_helpful').click(function() {
    $('#feedback_msg').show('slow');
  });

  //Do the actual submit, output status.
  $('#feedback_form').submit(function() {
     jQuery.post('<?=ADMIN_URL?>/pages/feedback', {
         'helpful': $('input[name=helpful]:checked').val(),
         'message': $('textarea[name=message]').val(),
         'email': $('input[name=email]').val(),
         'human': $('input[name=human]').val(),
         'page_id': '<?=$this->page['id']?>',
         'submit': $('input[name=submit]').val()
       }, function(data) {
         $('#feedback_form').after(data+"<br/>");
     });
     return false;
  });

});
</script>

<div id="feedback_box">
	<div id="feedback_inner">
		<h1>Was this information helpful?</h1>
		<h2>Your input can help us improve the Concerto Support Center.</h2>
		
		<form id="feedback_form" method="POST" action="<?=ADMIN_URL?>/pages/feedback">
			<p id="feedback_helpful">
				<input type="radio" name="helpful" value="1" id="feedback_yes">
				<label for="helpful" id="feedback_yes_lbl">Yes</label>
				</input>
				<input type="radio" name="helpful" value="0" id="feedback_no"/>
				<label for="helpful" id="feedback_no_lbl">No</label>
			</p>
			<p id="feedback_msg">
				<label for="message">What can we do to improve this page?</label><br/>
				<textarea cols="40" rows="4" name="message"></textarea><br/>
		<? if(isLoggedIn()) { ?>
				<input type="hidden" name="email" value="" />
				<input type="hidden" name="human" value="person" /><br/>
		<? } else { ?>
				<label for="email">Your Email: <em>(optional)</em></label><br />
				<input type="text" name="email" value="" /><br/>
				<label for="human">Are you a person or a robot?: <em>(please type 'person' or 'robot')</em></label><br />
				<input type="text" name="human" value="" /><br/>
		<? } ?>
		
				<input type="hidden" name="page_id" value="<?=$this->page['id']?>" />
				<input value="Submit Feedback" type="submit" name="submit" />
			</p>
		</form>
	</div>
</div>