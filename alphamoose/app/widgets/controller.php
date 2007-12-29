<?php
class widgetsController extends Controller
{
	public function index()
	{
		global $sess;
		$sess[pagetitle] = "Widget Listing";
		$sess[messages][]= array('info','Widgets successfuly 
listed');
		$sess[mywidgets] = split(',','x,y,z,t,bar,ha ha');
		self::renderView('list');
	}
	public function show()
	{
		global $sess;
		$sess[mywidget] = 'this is widget'.$sess['args'][0].'!';
		self::renderView('show');
	}
}
?>
