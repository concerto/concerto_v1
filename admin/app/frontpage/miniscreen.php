<?

$output = Array();

if(is_array($this->graphics) && count($this->graphics) > 0){
  foreach($this->graphics as $graphic){
    $output['graphics'][] = ADMIN_URL . '/content/image/' . $graphic->id;
  }
} else {
  $output['graphics'][] = ADMIN_URL . '/PATH TO DEFAULT IMAGE';
}
if(is_array($this->text) && count($this->text) > 0){
  foreach($this->text as $text){
    $output['text'][] = $text->content;
  }
} else {
  $output['text'][] = "<h1>Union Events Calendar</h1><h2>Register for Sorority Recruitment</h2><h3>Mon, August 24, 2009 4:00 PM - September 7, 2009, 4:00 PM CII</h3><h2>Student Senate Meetings</h2><h3>Tue, September 1, 2009 4:30 PM - 6:30 PM Rensselaer Union Room 3202</h3><h2>Finance, Facilities, & Advancement  (Student Senate)</h2><h3>Wed, September 2, 2009 9:00 PM - 10:00 PM Rensselaer Union Room 3606 (Shelnutt Gallery)</h3>";
}
if(is_array($this->ticker) && count($this->ticker) > 0){
  foreach($this->ticker as $ticker){
    if(strlen($ticker->content) > 98){
      $output['ticker'][] = substr($ticker->content,0,100) . '...';
    } else {
      $output['ticker'][] = $ticker->content;
    }
  }
} else {
  $output['ticker'][] = "Welcome to Concerto!";
}
echo json_encode($output);
?>
