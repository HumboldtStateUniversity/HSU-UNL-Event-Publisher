$ = WDN.jQuery;

//attaching JS events
var glob_handler = {

  init: function() {
		
  //if it is a month view
  if (document.getElementById('month_viewcal')){
  	if(readCookie('monthview') ==null){
  		monthdisplay(); 
  		var viewall = document.getElementById('monthfullview');
  		viewall.className = 'monthfullview_unchecked';
  	}
  	else{
  		var monthID = document.getElementById('month_viewcal');
 		var LImonth = monthID.getElementsByTagName('ul');
  			for(m=0; m<LImonth.length; m++){
  				LImonth[m].className = 'unhide';
  			}
  	}
  }
  //if it is event day and instance view
  else{
 	 if(getElementsByClassName(document, "div", "year_cal").length == 0 && getElementsByClassName(document, "ul", "search").length == 0){
 	 	ajaxsearch();
 	 	shortenText();
 		dropdown();
 	 }
 	glob_handler.addEvent(document.getElementById('monthwidget'), 'DOMMouseScroll', changeMonthWidget);
  }
  
  todayHilite();	
  
  //attach search tips if cookie does not exist
  if(readCookie('searchtips') ==null){
  	searchinfo(); 
  }
	  
  },
  
  addEvent: function( obj, type, fn ) {
    if ( obj.attachEvent ) {
      obj['e'+type+fn] = fn;
      obj[type+fn] = function(){obj['e'+type+fn]( window.event );}
      obj.attachEvent( 'on'+type, obj[type+fn] );
    } else {
      obj.addEventListener( type, fn, false );
    }
  }
}
glob_handler.addEvent(window,"load",glob_handler.init);

try {
	WDN.navigation.initialize = function(){};
} catch(e){}

function changeMonthWidget(e)
{
	e = e ? e : window.event;
	var raw = e.detail ? e.detail : e.wheelDelta;
	if (raw > 0) {
		// Go to next month
		var next = document.getElementById('next_month').getAttribute("href", 2)+'?&monthwidget&format=hcalendar';
	} else {
		// Go to prev month
		var next = document.getElementById('prev_month').getAttribute("href", 2)+'?&monthwidget&format=hcalendar';
	}
	ajaxEngine(next, 'monthwidget');
	return cancelEvent(e);
}

/*------------------------ GENERIC FUNCTIONS --------------------------*/ 
/* getElementsByClassName by some guy with a yeallowish website. */
function getElementsByClassName(oElm, strTagName, strClassName){
    var arrElements = (strTagName == "*" && oElm.all)? oElm.all : oElm.getElementsByTagName(strTagName);
    var arrReturnElements = new Array();
    strClassName = strClassName.replace(/\-/g, "\\-");
    var oRegExp = new RegExp("(^|\\s)" + strClassName + "(\\s|$)");
    var oElement;
    for(var i=0; i<arrElements.length; i++){
        oElement = arrElements[i];      
        if(oRegExp.test(oElement.className)){
            arrReturnElements.push(oElement);
        }
    }
    return (arrReturnElements);
}

/* compressed browser detection (http://www.quirksmode.org/js/detect.html) */
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?"":e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[(function(e){return d[e]})];e=(function(){return'\\w+'});c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('a A={E:g(){6.t=6.h(6.D)||"Z k t";6.G=6.f(3.8)||6.f(3.I)||"r k G";6.s=6.h(6.z)||"r k s"},h:g(7){M(a i=0;i<7.B;i++){a 9=7[i].4;a H=7[i].q;6.l=7[i].b||7[i].2;d(9){d(9.v(7[i].5)!=-1)c 7[i].2}N d(H)c 7[i].2}},f:g(9){a p=9.v(6.l);d(p==-1)c;c S(9.U(p+6.l.B+1))},D:[{4:3.8,5:"m",b:"m/",2:"m"},{4:3.e,5:"X",2:"Y"},{q:J.K,2:"L"},{4:3.e,5:"x",2:"x"},{4:3.e,5:"O",2:"P"},{4:3.8,5:"u",2:"u"},{4:3.e,5:"w",2:"w"},{4:3.8,5:"n",2:"n"},{4:3.8,5:"F",2:"T",b:"F"},{4:3.8,5:"V",2:"o",b:"W"},{4:3.8,5:"o",2:"n",b:"o"}],z:[{4:3.j,5:"Q",2:"R"},{4:3.j,5:"y",2:"y"},{4:3.j,5:"C",2:"C"}]};A.E();',62,62,'||identity|navigator|string|subString|this|data|userAgent|dataString|var|versionSearch|return|if|vendor|searchVersion|function|searchString||platform|unknown|versionSearchString|OmniWeb|Netscape|Mozilla|index|prop|an|OS|browser|Firefox|indexOf|Camino|iCab|Mac|dataOS|BrowserDetect|length|Linux|dataBrowser|init|MSIE|version|dataProp|appVersion|window|opera|Opera|for|else|KDE|Konqueror|Win|Windows|parseFloat|Explorer|substring|Gecko|rv|Apple|Safari|An'.split('|'),0,{}))

/* cookie function */
function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}

/* Go to a given URL */
function gotoURL(location) {document.location=location;}
/*------------------------------------------------------------------------*/

/*
 * subscriber drop down for ie, adjust css when min height is triggered
 * Call from: closeULbox(), showMoreEvents(), todayHilite()
 * Call to: none
 */
var g_bH = false; 
  function dropdown(p_strId) {
    g_bH = false;
    var l_E = document.getElementById(p_strId);

    if(l_E && document.defaultView) {
      if(document.defaultView.getComputedStyle(l_E, 'hover')) {
        g_bH = true;    
      }
    }
    l_E = null;
   try{ 
   var dr = document.getElementById('droplist').style;
   var rightCol = getElementsByClassName(document, "div", "col");
 rightCol[0].style.Height = '768px';
 dr.position = 'relative';
   }catch(e){};
  }

/*
 * Toggle off monthview() based on cookie
 * Call from: none
 * Call to: none
 */
function fullview(){
	if (readCookie('monthview') ==null){
	createCookie('monthview','showallevents',1);
	window.location.reload();
	
	}else{
	eraseCookie('monthview');
	window.location.reload();
	}
return false;
}

/*
 * Return full month strings based on javascript date function
 * Call from: closeULbox(), showMoreEvents(), todayHilite()
 * Call to: none
 */
function getCalendarDate(t)
{
   var months = new Array(13);
   months[0]  = "January";
   months[1]  = "February";
   months[2]  = "March";
   months[3]  = "April";
   months[4]  = "May";
   months[5]  = "June";
   months[6]  = "July";
   months[7]  = "August";
   months[8]  = "September";
   months[9]  = "October";
   months[10] = "November";
   months[11] = "December";
   if (t){
   	  var monthname = months[t];
   	  return monthname;
   }

   var now         = new Date();
   var monthnumber = now.getMonth();
   var monthname   = months[monthnumber];
   var dateString  = monthname;
   return dateString;
}

function eventLink(){
	$('tbody a.url').click(function(){
		var linkURL = this.getAttribute("href", 2)+'?&format=hcalendar';
		new ajaxEngine(linkURL, 'eventlisting');
		return false;
	});
}

/*
 * Determines whether a link is to the internal system or external.
 */
function isInternalLink(link)
{
	var baseURL = document.getElementById('todayview');
	//baseURL.childNodes[0].getAttribute("href", 2)
	if (link.getAttribute('href').indexOf('http') == 0 && link.getAttribute('href').indexOf('events.unl.edu') < 0 ) {
		return false;
	} else {
		return true;
	}
}

/*
 * Go back to today's date in month widget
 * Call from: addLoadEvent
 * Call to: none
 */
function returnToday(){
	var x = new Date();	

	$("#load").html("<img src='/ucomm/templatedependents/templatecss/images/loading.gif' />");

	//due to the way we detect today's date, the left side content has to be loaded before the month widget
	var backtoDay = window.location.href+'?&y='+(x.getYear ()+1900)+'&m='+(x.getMonth()+1)+'&d='+x.getDate()+'&format=hcalendar';
	ajaxEngine(backtoDay, 'eventlisting');
	$.get(window.location.href+'?&y='+(x.getYear()+1900)+'&m='+(x.getMonth()+1)+'&monthwidget&format=hcalendar', function(data, textStatus) {
		$("#load").html('');
		$("#monthwidget").html(data);
		todayHilite();			
	}, false, null);
	return false;
}

/*
 * today icon and ajax initialization for month widget
 * Call from: addLoadEvent
 * Call to: none
 */

function todayHilite(){
	try {
	x = new Date ();
	y = x.getDate ();
	var td0 = getElementsByClassName(document, "table", "wp-calendar");
	var spanID = document.getElementById(getCalendarDate());
	
	var td1 = td0[0].getElementsByTagName('td');
		var verify = getElementsByClassName(td0[0], "span", "monthvalue");
		//if in day view (only), execute....
		var idSelector = document.getElementById('frontend_view_selector');
		if (idSelector.className == 'day' || idSelector.className == 'event' || idSelector.className == 'upcoming'){
			createButton('Return to today', document.getElementById('monthwidget'), returnToday, 'returnToday');
			if(idSelector.className == 'day'){
				monthNav(); 
			}
			eventLink();
			monthCaptionSwitch(td0[0]);
			for(i=0;i<td1.length;i++){
				monthWidget(td1[i]);
			}
		}

		if(idSelector.className != 'year' && idSelector.className != 'upcoming'){	
			var selectedDay = getElementsByClassName(document.getElementById('maincontent'), "h4", "sec_main");
			var re = new RegExp(/\d+/);
			var m = re.exec(selectedDay[0].childNodes[0].nodeValue);
			var dayT, todayT;
			var todayFlag = false;
			//indicate today
			for(i=0;i<td1.length;i++){
				
				if(td1[i].className.indexOf('prev') < 0 && td1[i].className.indexOf('next') < 0){
					
					if(td1[i].firstChild.nodeName == 'A'){
						dayT = td1[i].firstChild.childNodes[0].nodeValue.indexOf(m[0]);
						todayT = td1[i].firstChild.childNodes[0].nodeValue.indexOf([y]);
					}else{
						dayT = td1[i].firstChild.nodeValue.indexOf(m[0]);
						todayT = td1[i].firstChild.nodeValue.indexOf([y]);
					}
				
					if(m != null && dayT >= 0 && document.getElementById('onselect') == null){
						td1[i].id = 'onselect';
					}
				
					//insert icon to indicate today	
					if(verify[0].id == getCalendarDate() && !todayFlag){
							try{
								
								if(todayT >= 0){
									td1[i].className += ' today'
									var imageToday = document.createElement("div");
									imageToday.setAttribute("id","today_image");
									td1[i].appendChild(imageToday);
									todayFlag = true;
								}
							}
							catch(e){}	
					}
				}
			}
			todayFlag = false;
		}
		
		else{
				
			for(q=0;q<td0.length;q++){
				var verify1 = getElementsByClassName(td0[q], "span", "monthvalue");
				var td1 = td0[q].getElementsByTagName('td');
				//indicate today
				for(i=0;i<td1.length;i++){
						//insert icon to indicate today	
					if(verify1[0].id == getCalendarDate() && td1[i].className.indexOf('prev') < 0 && td1[i].className.indexOf('next') < 0){
							try{
								if(td1[i].innerHTML.match("<a href.*>"+y+"</a>|"+y)){
									td1[i].className += ' today'
									var imageToday = document.createElement("div");
									imageToday.setAttribute("id","today_image");
									td1[i].appendChild(imageToday);
									break;
								}
							}
							catch(e){}	
					}
					
				}
			}
		}
	} catch(e) {}
	if(!window.XMLHttpRequest){
  			fnLoadPngs();
  	}
}

/*
 * go through each table widget to set marker and launch ajax month engine if applicable
 * Call from: onkeyup, monthnav
 * Call to: none
 */
function widgetAttachEvent(e){
	var td0 = getElementsByClassName(document, "table", "wp-calendar");
	var td1 = td0[0].getElementsByTagName('td');
	for (i=0;i<td1.length;i++){
				if (td1[i].getAttribute("id") == 'onselect') {
					td1[i].id = 'none';
					if(e == 'prev'){
						td1[i-1].id = 'onselect';
						if(document.getElementById('onselect').className.indexOf('prev') > 0){
							var val_month = document.getElementById('prev_month').getAttribute("href", 2)+'?&monthwidget&format=hcalendar';
							new ajaxEngine(val_month, 'monthwidget');
						}
					}
					else{
						td1[i+1].id = 'onselect';
						if(document.getElementById('onselect').className.indexOf('next') > 0){
							var val_month = document.getElementById('next_month').getAttribute("href", 2)+'?&monthwidget&format=hcalendar';
							new ajaxEngine(val_month, 'monthwidget');
						}
					}				
					break;
				}
	}
}

var timerID = null;
document.onkeyup = function(event){
if(document.getElementById('day_nav')){
	
	var arrowNav = document.getElementById('day_nav');
	//reset timer
	if (timerID != null) {
	  window.clearTimeout(timerID);
	} 
	
	var evt = event || window.event;
	var el = evt.srcElement || evt.target;
	if (el.name)
	return true;
		
		//if it is right arrow key => next date
		if (evt.keyCode == 39) {
			arrowNav.getElementsByTagName('a')[1].id = 'ac';			
			widgetAttachEvent('next');
			if(document.getElementById('onselect').childNodes[0].nodeType == 1){
				timerID = window.setTimeout(_ajaxKeyNav, 500);
			}
			return false;
		}
		
		//if it is left arrow key => previous date
		else if (evt.keyCode == 37) {		 
			arrowNav.getElementsByTagName('a')[0].id = 'dc';
			widgetAttachEvent('prev');			
			if(document.getElementById('onselect').childNodes[0].nodeType == 1){
				timerID = window.setTimeout(_ajaxKeyNav, 500);
			}
			return false;
		}
	return true;	
}
};

//key nav function call.
function _ajaxKeyNav(){
	var url = document.getElementById('onselect').childNodes[0].getAttribute("href", 2)+'?&format=hcalendar';
	ajaxEngine(url, 'eventlisting');
	return false;
}

/* top right arrows navigator*/
function monthNav(){
	var nav_prev1 = document.getElementById('day_nav');
	var linkprev = nav_prev1.getElementsByTagName('a')[0].getAttribute("href", 2)+'?&format=hcalendar';
	var linknext = nav_prev1.getElementsByTagName('a')[1].getAttribute("href", 2)+'?&format=hcalendar';
		
	nav_prev1.getElementsByTagName('a')[0].onclick=function(){
		widgetAttachEvent('prev');
		new ajaxEngine(linkprev, 'eventlisting');	
		return false;
	};
	
	nav_prev1.getElementsByTagName('a')[1].onclick=function(){
		widgetAttachEvent('next');	
		new ajaxEngine(linknext, 'eventlisting');	
		return false;
	};
}

/*month widget day TD navigator */
function monthWidget(tD){
	if (tD.className.indexOf('selected') >= 0){
			tD.style.cursor = 'pointer';
			var daylink = tD.getElementsByTagName('a')[0];
				tD.onclick = function(){
					if(document.getElementById('onselect')){
							document.getElementById('onselect').id = 'none';						
					}
					this.id = 'onselect';					
					if(this.className.indexOf('next') > 0){
						var nextmonth = document.getElementById('next_month').getAttribute("href", 2)+'?&monthwidget&format=hcalendar';
						new ajaxEngine(nextmonth, 'monthwidget');
					}
					else if(this.className.indexOf('prev') > 0){
						var prevmonth = document.getElementById('prev_month').getAttribute("href", 2)+'?&monthwidget&format=hcalendar';
						new ajaxEngine(prevmonth, 'monthwidget');
					}					
					var link = daylink.getAttribute("href", 2)+'?&format=hcalendar';
					new ajaxEngine(link, 'eventlisting');				  				
					return false;
				}
	}
}

/*month widget caption month navigator*/
function monthCaptionSwitch(eT){
	var spanarrow = eT.getElementsByTagName('span');
	var spanlinkprev = spanarrow[0].childNodes[0].getAttribute("href", 2)+'?&monthwidget&format=hcalendar';
	var spanlinknext = spanarrow[3].childNodes[0].getAttribute("href", 2)+'?&monthwidget&format=hcalendar';
	spanarrow[3].childNodes[0].onclick=function(){new ajaxEngine(spanlinknext, 'monthwidget');return false;};
 	spanarrow[0].childNodes[0].onclick=function(){new ajaxEngine(spanlinkprev, 'monthwidget');return false;};
}

/*this is the main ajax calling engine. make library calls to XHR lib (ajaxcaller.js)*/
function ajaxEngine(urlPath, section, vars) {
	document.getElementById('load').innerHTML="<img src='/ucomm/templatedependents/templatecss/images/loading.gif' />";
	switch (section){
		case "monthwidget":
			$.get(urlPath, onMonthResponse);
			break;
		case "search":
			$.get(urlPath, onSumResponse);
			break;
		case "eventlisting":
			$.get(urlPath, onSumResponse);
		break;
		default : alert("Error: please specify ajaxEngine calling section");
	}	
}

/* parse ajax response for month widget */
function onMonthResponse(data, textStatus) {
	if (document.getElementById('onselect')
		&& document.getElementById('onselect').getElementsByTagName('a')[0] != null){
		var tdlink = document.getElementById('onselect').getElementsByTagName('a')[0].getAttribute("href", 2);
	} 
	$('#load').html("");
	$('#monthwidget').html(data);
	carryOver(tdlink);
	todayHilite();
}

/* parse ajax response for event listing and event instance */
var save;//global variable to store previous content;
function onSumResponse(data, textStatus) {
  save = $('#updatecontent').html();
  $('#load').html("");
  $("#updatecontent").html(data);
  new eventLink();
  shortenText();
  if(document.getElementById('day_nav') != null){
 	 new monthNav(); 
  }
  else if(getElementsByClassName(document, "div", "event_cal").length > 0){
  	if(document.getElementById('returnPrevScreen') == null){
    	 CBInsertBefore('< Return to events listing', function(){returnPrevScreen(save);return false;}, 'returnPrevScreen');
  	 }
  	 else{
  	 	save = '';
  	 }
  }
}

//this function carries over onselect value from prior month widget
function carryOver(ss){
var td0 = getElementsByClassName(document, "table", "wp-calendar");
var td1 = td0[0].getElementsByTagName('a');
var td2 = td0[0].getElementsByTagName('td');
//alert(ss);
for(l=0;l<td1.length;l++){
		if(td1[l].getAttribute("href", 2).indexOf(ss) >= 0){			
			//alert(td1[l].getAttribute("href", 2));
			td1[l].parentNode.id = 'onselect';
			break;
		}		
	}
}

function returnPrevScreen(prev_content){
 document.getElementById("updatecontent").innerHTML = prev_content;
 new eventLink();
 if(document.getElementById('day_nav') != null){
 	new monthNav();
 }
 save = '';
}

/*
 * Create <a href> buttons using insertBefore
 * Call from: onSumResponse()
 * Call to: returnPrevScreen()
 */
function CBInsertBefore(linktext, actionFunc, classN){
	var morelink = document.createElement("a");
	morelink.style.display = 'inline';
	var text = document.createTextNode(linktext);
	morelink.className=classN;
	morelink.href = '#';
	morelink.onclick = actionFunc;
	morelink.appendChild(text);
	var c = document.getElementById('updatecontent');
	c.insertBefore(morelink, getElementsByClassName(document, "div", "event_cal")[0]);
}

/*
 * Ajax search
 * Call from: addevent
 * Call to: onSearchResponse
 */
function ajaxsearch(){
	var searchForm = document.getElementById('event_search');
	var searchSubmit = searchForm.getElementsByTagName('input')[1];
	var fp = document.getElementById('event_search');
	var formAction = fp.action;
	formAction = formAction.substring(0,formAction.indexOf('search'));

	document.event_search.onsubmit = function(){
		var searchVal = document.getElementById('searchinput').value;
		while (searchVal.indexOf('#')>-1) {
			searchVal = searchVal.replace(/#+/,'');
		}
		var searchVars = new Array();
		searchVars['q'] = searchVal;
		searchVars['format'] = 'hcalendar';
		searchVars['search'] = 'search';
		document.getElementById('load').innerHTML = '<img src="/ucomm/templatedependents/templatecss/images/loading.gif" />';
		ajaxEngine(formAction, 'search', searchVars)
		return false;
	}
}



/*
 * Search box tips
 * Call from: none
 * Call to: none
 */
function searchinfo(){
	var nav_prev1 = document.getElementById('day_nav');
	var search = document.forms.event_search.q;
	var flagappeared = document.getElementById('search_term');
	
	search.onclick = function(){							
								if(nav_prev1 && nav_prev1.style.display != 'inline'){
									nav_prev1.style.display = 'none';
								}
									if(!flagappeared.className){
										createCookie('searchtips','searchterms',7);
										Spry.Effect.AppearFade("search_term", {duration: 1000, from: 0, to: 100, toggle: true, finish: window.setTimeout(finishSearch, 8000)});
										flagappeared.className = 'appeared';
										flagappeared.style.display = 'block';																					
									} else {
										flagappeared.style.display = 'none';	
									}							
								};
					
	var top_off = document.forms.event_search.getElementsByTagName('a');
	top_off[0].onclick = function(){
									var formseaarch = document.forms.event_search.q;
									nav_prev1.style.display = 'inline';
									Spry.Effect.AppearFade("search_term", {duration: 1000, from: 100, to: 0, toggle: true});
									formseaarch.focus();
									flagappeared.style.display = 'none';	
									return false;
									};
									
}

/*auto fade out */
function finishSearch(){	
	Spry.Effect.AppearFade("search_term", {duration: 1000, from: 100, to: 0, toggle: true, finish:function(){var nav_prev1 = document.getElementById('day_nav');nav_prev1.style.display = 'inline';}	});
}
/*
 * Clean and simple month display
 * Call from: addLoadEvent
 * Call to: createButton(), truncate()
 */
function monthdisplay(){
	var td0 = getElementsByClassName(document, "table", "wp-calendar");
	for(j=0;j<td0.length;j++){
		var td1 = td0[j].getElementsByTagName('td');
		for(i=0;i<td1.length;i++){
			var listevent = td1[i].getElementsByTagName('li');
			if(listevent.length != 0){
				if (listevent.length > 4){
					var how_many_more = listevent.length - 4;
					for( var d=4; d<listevent.length; d++){
						listevent[d].style.display = 'none';
					}
					createButton("+"+how_many_more+" more", td1[i], showMoreEvents, "more_event");
				}
				else{
					createButton("full view", td1[i], showMoreEvents, "more_event");
				}
				for (k=0;k<listevent.length;k++){
				var listText = listevent[k].getElementsByTagName('a');
					for(x=0; x<listText.length; x++){
					  truncate(listText[x], '12');
					}
				}
			}
		}
	}
}

/*
 * Create <a href> buttons
 * Call from: monthdisplay()
 * Call to: showMoreEvents()
 */
function createButton(linktext, attachE, actionFunc, classN){
	var currentView =  document.getElementById('frontend_view_selector').className;
	var hreflink;	
	var morelink = document.createElement("a");
	morelink.style.display = 'inline';
	var text = document.createTextNode(linktext);
	morelink.className=classN;	
	if(currentView == 'upcoming'){
		hreflink = 	document.getElementById('todayview').childNodes[0].href;
	} else {
		hreflink = '#';	
		morelink.onclick = actionFunc;
	}	
	morelink.href = hreflink;
	morelink.appendChild(text);
	attachE.appendChild(morelink);
}

function shortenText(){
	var text = getElementsByClassName(document, "blockquote", "description");
	for(var t = 0; t<text.length; t++){
		truncate(text[t], 350);
	}
}


/*
 * Truncate text 
 * Call from: monthdisplay()
 * Call to: Del()
 */
function truncate(t, num){
	var len = num;
	var trunc = t.innerHTML;
	// rinse text to weed out any html tags
	trunc = Del(trunc);
	if (trunc.length > len) {
	   /* Truncate the content of the P, then go back to the end of the
	      previous word to ensure that we don't truncate in the middle of
	      a word */
	    trunc = trunc.substring(0, len);
	    //trunc = trunc.replace(/\w+$/, '');
		/* Add an ellipses to the end and make it a link that expands
       	   the paragraph back to its original size */
   try{	 
   	trunc += ' ...<span style="display:none">'+t.innerHTML;
	t.innerHTML = trunc;
	}catch(e){};
	}						
}

/*
 * Weed out HTML tags
 * Call from: truncate()
 * Call to: none
 */	
function Del(Word) {
a = Word.indexOf("<");
b = Word.indexOf(">");
len = Word.length;
c = Word.substring(0, a);
if(b == -1)
b = a;
d = Word.substring((b + 1), len);
Word = c + d;
tagCheck = Word.indexOf("<");
if(tagCheck != -1)
Word = Del(Word);
return Word;
}

/*
 * onclick function to view the rest of the events. All the actions are here :)
 * Call from: createButton()
 * Call to: showDate(), adjustPos(), closeULbox();
 */	
var zInde = 1000;
function showMoreEvents(){

	var ul = this.previousSibling;
	var tdcell = ul.parentNode;
	var monthL = getElementsByClassName(document.getElementById('month_viewcal'), "span", "monthvalue");

	//if it's today, give different id to td to counteract position relative for today TD and hide today icon
	if(monthL[0].id == getCalendarDate()){
	if(tdcell.id == 'today'){
	document.getElementById('today_image').style.display = 'none';
	tdcell.setAttribute("id","tt");
	}
	}
	
	//stack ulbox according to 'who click first' and restack if it is clicked again.
	ul.className = "ul_box";
	ul.style.zIndex = zInde;
	zInde++;
	ul.onclick = function(){
	this.style.zIndex = zInde+1;
	zInde++;
	};
	
	var li = ul.getElementsByTagName('li');
	
	//get the month value and pass it through showDate function
	//var monthL = getElementsByClassName(document.getElementById('month_viewcal'), "span", "monthvalue");
	var yearL = getElementsByClassName(document, "span", "yearvalue");
	var now         = new Date();
 	var monthnumber = now.getMonth();
	var monthval = getCalendarDate(tdcell.childNodes[1].childNodes[0].nodeValue-1);

	if (tdcell.className == 'prev'){
	showDate(ul, li, monthval, yearL[0].firstChild.childNodes[0].nodeValue);
	}else if (tdcell.className == 'next'){
	showDate(ul, li, monthval, yearL[0].firstChild.childNodes[0].nodeValue);
	}else{
	showDate(ul, li, monthval, yearL[0].firstChild.childNodes[0].nodeValue);
	}
	
	for (i=0;i<li.length;i++){
		var listText = li[i].getElementsByTagName('a');
			for(x=0; x<listText.length; x++){
				var everything = listText[x].getElementsByTagName('span');
				if (everything.length > 0){
				listText[x].innerHTML = everything[0].innerHTML;
				}
			}
	}
		var para = document.createElement("li");
		para.className = 'close_eventbox';
		var text = document.createTextNode("close");
		var elip_link = document.createElement('a');
		elip_link.href = '#';
		elip_link.onclick = closeULbox;
		elip_link.appendChild(text);
		para.appendChild(elip_link);
		ul.appendChild(para);

	//find position and adjust accordingly so there's no overflow on ul_box
	adjustPos(ul, tdcell);
	return false;
}

/*
 * contruct the date when ul_box is brought up. Format: September 12, 2006
 * Call from: showMoreEvents()
 * Call to: none
 */	
function showDate(ulList, liList, m, y){
	var date = ulList.parentNode.firstChild.childNodes[0].nodeValue;
	var dateUL = document.createElement("li");
	var dateTEXT = document.createTextNode(m+' '+date+','+' '+y);
	var dateLink = document.createElement('a');
	dateUL.className="dateUL";
	dateLink.href = ulList.parentNode.firstChild;
	dateLink.appendChild(dateTEXT);
	dateUL.appendChild(dateLink);
	ulList.insertBefore(dateUL,liList[0]);
}

/*
 * calculate pop up position and apply bottom and right = 0 to prevent overflow.
 * Call from: showMoreEvents()
 * Call to: findPos()
 */	
function adjustPos(ulL, tD){
	var pos = findPos(ulL.parentNode.parentNode.parentNode.parentNode);
	var ulpos = findPos(ulL);
	var footpos = findPos(document.getElementById('footer'));
	var tdHeight = tD.clientHeight;
	var version=0;
	var widthOffset;
	
	var theWidth = 0;
    if (window.innerWidth) {
	theWidth = window.innerWidth
    } else if (document.documentElement &&
                document.documentElement.clientWidth) {
	theWidth = document.documentElement.clientWidth
    } else if (document.body) {
	theWidth = document.body.clientWidth
    }
    
	widthOffset = ((theWidth-pos[0])/ulpos[0])*100;
   	if(ulpos[1]+ulL.clientHeight > (footpos[1]-20)){
	ulL.style.bottom = '0';
	}
	if (widthOffset < 120){
	ulL.style.right = '0';
	}		
}

/*
 * return offset value x and y of an object
 * Call from: adjustPos()
 * Call to: none
 */	
function findPos(obj)
{
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		curleft = obj.offsetLeft
		curtop = obj.offsetTop
		while (obj = obj.offsetParent) {
			curleft += obj.offsetLeft
			curtop += obj.offsetTop
		}
	}
	return [curleft,curtop];
}

/*
 * close and retruncate ul_box
 * Call from: showMoreEvents()
 * Call to: truncate()
 */	
function closeULbox(){
var monthL = getElementsByClassName(document.getElementById('month_viewcal'), "span", "monthvalue");
var ul = this.parentNode.parentNode;
	
/* -- Today icon is moving!!! Bug -- */
/*if(monthL[0].id == getCalendarDate()){
document.getElementById('today_image').style.display = 'inline';
ul.parentNode.id = "today";
}*/

ul.className = 'none';
ul.removeChild(ul.lastChild);
ul.removeChild(ul.getElementsByTagName('li')[0]);
var listevent = ul.getElementsByTagName('li');
for (k=0;k<listevent.length;k++){
				var listText = listevent[k].getElementsByTagName('a');
					for(x=0; x<listText.length; x++){
					  truncate(listText[x]);
					}
				}

return false;
}

function cancelEvent(e)
{
  e = e ? e : window.event;
  if(e.stopPropagation)
    e.stopPropagation();
  if(e.preventDefault)
    e.preventDefault();
  e.cancelBubble = true;
  e.cancel = true;
  e.returnValue = false;
  return false;
}

