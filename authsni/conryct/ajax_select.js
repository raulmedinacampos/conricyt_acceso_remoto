// Multiple select lists - www.coursesweb.net/ajax/

// function used to remove the next lists already displayed when it chooses other options
function removeLists(colid) {
  var z = 0;
  // removes data in elements with the id stored in the "ar_cols" variable
  // starting with the element with the id value passed in colid
  for(var i=1; i<ar_cols.length; i++) {
    if(ar_cols[i]==null) continue;
    if(ar_cols[i]==colid) z = 1;
    if(z==1) document.getElementById(preid+ar_cols[i]).innerHTML = '';
  }
}

// create the XMLHttpRequest object, according browser
function get_XmlHttp() {
  // create the variable that will contain the instance of the XMLHttpRequest object (initially with null value)
  var xmlHttp = null;

  if(window.XMLHttpRequest) { xmlHttp = new XMLHttpRequest(); }     // for Forefox, IE7+, Opera, Safari
  else if(window.ActiveXObject) { xmlHttp = new ActiveXObject("Microsoft.XMLHTTP"); }      // IE5 or 6

  return xmlHttp;
}

// sends data to a php file, via POST, and displays the received answer
function ajaxReq(col, wval) {
  removeLists(col);           // removes the already next selects displayed

  // if the value of wval is not '- - -' and '' (the first option)
  if(wval!='- - -' && wval!='') {
    var request =  get_XmlHttp();		      // call the function with the XMLHttpRequest instance
    var php_file = 'select_list.php';     // path and name of the php file

    // create pairs index=value with data that must be sent to server
    var  data_send = 'col='+col+'&wval='+wval;

    request.open("POST", php_file, true);			// set the request

    document.getElementById(preid+col).innerHTML = 'Loadding...';   // display a loading notification

    // adds a header to tell the PHP script to recognize the data as is sent via POST
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(data_send);	      	// calls the send() method with data_send

    // Check request status
    // If the response is received completely, will be added into the tag with id value of "col"
    request.onreadystatechange = function() {
      if (request.readyState==4) {
        document.getElementById(preid+col).innerHTML = request.responseText;
      }
    }
  }
}