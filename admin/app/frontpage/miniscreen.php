<?

$output = Array();

if(is_array($this->graphics)){
  foreach($this->graphics as $graphic){
    $output['graphics'][] = ADMIN_URL . '/content/image/' . $graphic->id;
  }
}
if(is_array($this->text)){
  foreach($this->text as $text){
    $output['text'][] = $text->content;
  }
}
if(is_array($this->ticker)){
  foreach($this->ticker as $ticker){
    if(strlen($ticker->content) > 98){
      $output['ticker'][] = substr($ticker->content,0,100) . '...';
    } else {
      $output['ticker'][] = $ticker->content;
    }
  }
}
echo json_encode($output);
?>
