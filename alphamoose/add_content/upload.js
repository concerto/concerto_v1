//see http://www.alistapart.com/articles/userproofingajax/
//essentially, add_post is used here to load the broadcast times for
// a production as soon as it is selected.


/* Generic error handler.
 *
 * Currently just an alert, but we'll turn it into a DIV or something
 * later on.
 */
function error(msg) {
	document.getElementById("error_msg").innerHTML = msg;
	document.getElementById("error_msg").style.display = "block";
}

function clear_error() {
	document.getElementById("error_msg").style.display = "none";
}

function start_wait() {
//!!!	document.getElementById("wait_error_message").style.display = "none";
	document.getElementById("wait_indicator").style.display = "inline";	
	
	/* Wait ten seconds, then call the wait error check.
	 */
	window.setTimeout("wait_error_timer()", 10000);
}

function clear_wait() {
	document.getElementById("wait_indicator").style.display = "none";
//!!!	document.getElementById("wait_error_message").style.display = "none";
}

function stylize(style) {
        style.fontWeight="bold";
}
function unstylize(style) {
	if ( typeof ( style ) != "undefined" )
        style.fontWeight="normal";
}

function wait_error_timer(start_time) {
	if (req.readyState != 4) {	// while we're still waiting for a response
		document.getElementById("wait_error_message").style.display = "block";
	}
}


/* Let's go Ajax (clap clap clapclapclap)
 */
var req; // our request object
var srcel; //element that we want to stylize after loading
var oldel; //element that is currently stylized

/* Send out a request for an XML document.
 */
function loadXMLDoc(url,el) {
	if (window.XMLHttpRequest) { 	// native XMLHttpRequest object, Moz family
		req = new XMLHttpRequest();
		req.onreadystatechange = processReqChange;
		req.open("GET", url, true);
		req.send(null);
	} else if (window.ActiveXObject) {	// ActiveX for IE
		req = new ActiveXObject("Microsoft.XMLHTTP");
		if (req) {
			req.onreadystatechange = processReqChange;
			req.open("GET", url, true);
			req.send();
		}
	} else {
		return false;	// no Ajax methods supported
	}
//	alert(url);
//	alert(el);
	srcel=document.getElementById(el).style;
//	alert(srcel);
	return true;
}

/* Receive the response for the XML document.
 */
function processReqChange() {
	
	if (req.readyState == 4) {		// 4 is "complete"
		if (req.status == 200) {		// HTTP OK
			/* The response will contain different data depending on what
			 * function called it.  So we extract the only standard data:
			 * the calling function's name.  We'll extract the data from
			 * thhe global req at the function level.
			 */
			//if (!(response = req.responseXML.documentElement)) {
				// Earlier versions of IE make it this far before choking on the Ajax kool-aid,
				// so we provide a graceful exit to the process.
				// mike thinks ie deserves to choke. (actually, we're not ready to submit. fail.)
				//document.forms[0].submit();
			//	error("There was an error finding production information. This may be caused by a problem in early versions of IE.");
			//} else {
				//method = response.getElementsByTagName('method')[0].firstChild.data;
				//eval(method + "_get()");
				add_get()
			//}
		} else if (req.status == 0) {
			// user abort; do nothing
		} else {
			error("HTTP " + req.status + "<br /><br />A problem was encountered retrieving the XML data: \n" + req.statusText);
		}
	}
}

function abort_request() {
	req.abort();
}


/* Sends one arguments to ajax/add.php:
 * 		item_text:  the text of the item to add to the list
 *
 * Returns boolean directive:  does the form get submitted or not?
 *		- true if Ajax functionality is not supported,
 *		- false otherwise.
 */
function add_post() {
	clear_error();
	
	prodid = document.getElementById("prodtitle").options[document.getElementById("prodtitle").selectedIndex].value;
	//text = document.getElementById("item_text").value;
	url = "m_broadcasttime.php?prodid=" + prodid + "&for=m_broadcast";
	
	/* Make it so.
	 */
	if (loadXMLDoc(url)) {
		start_wait();
		return false; // do not submit the form
	} else {
		return true; // no Ajax compatibility; submit the form
	}
}

/* Receives the results of the add operation.
 * 		<success> 	- the success flag of the operation: 1 if OK, -1 if failed
 *		<index> 	- the list index of the added item
 *		<text> 		- the text of the added item
 */
function add_get() {
	response = req.responseText;	
	//errorMsg = response.getElementsByTagName('error')[0].firstChild.data;
	clear_wait();

	unstylize(oldel);
	stylize(srcel);
	oldel=srcel;
	
	//lists = response.getElementsByTagName('broadcastlist');
	target = document.getElementById('formcontent');
	
	target.innerHTML=response;
		
	//ep = document.createElement("p");
	///em = document.createElement("em");
	//em.innerHTML = "Production never broadcast.";
	//ep.appendChild(em);
	//target.appendChild(ep);
}

