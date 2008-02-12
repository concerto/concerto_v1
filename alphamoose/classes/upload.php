<?
/*
Class: Upload
Status: Yea right  
Functionality:
Comments: 
	The goal of upload is to process/clean things up before sending them to Content to be created.
	And then clean them up after content has had a chance to play

*/

class Uploader{
	/*How to decypher this:
	U = Sent by upload form
	C = Used by content class
	I = Internal use only
	*/
	var $name; //UC
	var $start_date; //UC
	var $end_date; //UC
	var $feeds; //U
	var $type; //C
	var $duration; //UC
	var $content_i; //U
	var $content_o; //C
	var $mime-type; //C
	var $user_id; //UC
	
	var $ctype; //UI
	var $auto; //I
	
	function __construct($name_in, $start_date_in, $end_date_in, $feeds_in, $duration_in, $content_i_in, $ctype_in, $user_id_in, $auto_in = 1){
		$this->name = $name;
		$this->start_date = $start_date_in;
		$this->end_date = $end_date_in;
		$this->duration = $duration_in;
		$this->content_i = $content_i_in;
		$this->ctype = $ctype_in;
		$this->user_id = $user_id_in;
		
		$this->feeds = split(',' $feeds_in);
		
		$this->auto = $auto_in; //This field specificies if the uploader should run in automatic mode or manual processing.  I like auto mode, but thats just me

		if($this->auto){
			$this->filer();
		} else {
			return true;
		}
	}
	//Determines which steps need to be applied to the content
	function filer(){
		if($ctype == 'text'){
			//Awsome, this is easy to handle!
			$this->content_o = $this->content_i;
			$this->mime-type = 'text/plain';
			$this->type_id = 2; //SELF: THIS IS BAD AND DUMB AND STUPID
			$content = new Content();
			if($content->create_content($this->name, $this->user_id, $this->content_o, $this->mime-type, $this->type_id, $this->duration, $this->start_time, $this->end_time)){
				$cid = $content->id;
				foreach($feeds as $fid){
					$f = new Feed($fid);
					$f->content_add($cid);
				}
				return true; //The content is finished uploading
			} else {
				return false; //Failure making a content isn't a good thing
			}
		
		} elseif($ctype == 'file'){
			if($this->content_i['error'] == 0 && is_uploaded_file($this->content_i['tmp_name'])){
				$pre_type = $this->typer();
			} else {
				return false;
			}
		} else {
			//Unknown ctype == bad
		}
	}
}
?>