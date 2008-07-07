<?=$this->page['content']?>
<?php
if($this->page['get_feedback']) {
   echo '<br style="clear:both">';
   include('_feedback.php');
}
?>