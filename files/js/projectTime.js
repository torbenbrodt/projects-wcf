var activetime = 0;
var activetimer = null;

var timeurl = "index.php?page=ProjectAjax&groupID="+groupID;
var cache = null;
function timeBack(ob) {
  ob.parentNode.parentNode.innerHTML = cache;
  return false;
}
function timeRev(ob) {
  var ajaxRequest = new AjaxRequest();
  ajaxRequest.openGet(timeurl+"&rev="+ob.innerHTML, function() {
	if(ajaxRequest.xmlHttpRequest.readyState == 4 && ajaxRequest.xmlHttpRequest.status == 200) {
		cache = ob.parentNode.parentNode.parentNode.parentNode.innerHTML;
		ob.parentNode.parentNode.parentNode.parentNode.innerHTML = ajaxRequest.xmlHttpRequest.responseText;
	}
  });
  return false;
}

function timeCheck() {
  if(activetime-- == 0) {
    var start = tl.getBand(0).getMinVisibleDate();
    var end = tl.getBand(0).getMaxVisibleDate();

    var ajaxRequest = new AjaxRequest();
    ajaxRequest.openPost("index.php?page=ProjectRevisionAjax&groupID="+groupID, 'start='+encodeURIComponent(start)+'&end='+encodeURIComponent(end), function() {
	if(ajaxRequest.xmlHttpRequest.readyState == 4 && ajaxRequest.xmlHttpRequest.status == 200) {
		window.clearInterval(activetimer);
		activetimer = null;

		var input = ajaxRequest.xmlHttpRequest.responseXML.getElementsByTagName('rev');
		var output = document.getElementById('revisionbody');
		
		var old = output.getElementsByTagName("tr");
		for(var i=old.length-1; i>=0; i--) {
			output.removeChild(old[i]);
		}

		for(var i=0; i<input.length; i++) {
			var id = input[i].getAttribute('id');
			var userID = input[i].getAttribute('userID');
			var author = input[i].getAttribute('author');
			var timestamp = input[i].getAttribute('timestamp');
			var msg = input[i].firstChild.data;
			
			var tr = document.createElement('tr');
			
			var td1 = document.createElement('td');
			td1.appendChild(document.createTextNode(id));
			tr.appendChild(td1);
			
			var td2 = document.createElement('td');
			if(userID > 0) {
				var child = document.createElement('a');
				child.setAttribute('href', 'index.php?page=User&userID='+userID);
				child.appendChild(document.createTextNode(author));
			} else {
				var child = document.createTextNode(author)
			}
			td2.appendChild(child);
			tr.appendChild(td2);
			
			var td3 = document.createElement('td');
			td3.appendChild(document.createTextNode(timestamp));
			td3.setAttribute('style','white-space: nowrap');
			tr.appendChild(td3);
			
			var td4 = document.createElement('td');
			td4.appendChild(document.createTextNode(msg));
			tr.appendChild(td4);
			
			output.appendChild(tr);
		}
	}
    });
  }
}

function timeScroll() {
  activetime = 5;
  if(activetimer == null) {
    activetimer = window.setInterval("timeCheck()", 400);
  }
}

var tl;
function timeLoad() {
  var eventSource = new Timeline.DefaultEventSource();
  var bandInfos = [
    Timeline.createBandInfo({
        eventSource:    eventSource,
        date:           timelineDate,
        width:          "60%", 
        intervalUnit:   Timeline.DateTime.DAY, 
        intervalPixels: 80
    }),
    Timeline.createBandInfo({
        eventSource:    eventSource,
        date:           timelineDate,
        showEventText:  false,
        width:          "25%", 
        intervalUnit:   Timeline.DateTime.MONTH, 
        intervalPixels: 200
    }),
    Timeline.createBandInfo({
        eventSource:    eventSource,
        date:           timelineDate,
        showEventText:  false,
        width:          "15%", 
        intervalUnit:   Timeline.DateTime.YEAR, 
        intervalPixels: 350
    })
  ];
  
  bandInfos[1].syncWith = 0;
  bandInfos[1].highlight = true;
  bandInfos[2].syncWith = 0;
  bandInfos[2].highlight = true;

  tl = Timeline.create(document.getElementById("my-timeplot"), bandInfos);
  tl.getBand(0).addOnScrollListener(timeScroll);
  Timeline.loadXML(timeurl, function(xml, url) { eventSource.loadXML(xml, url); });
}

var resizeTimerID = null;
function timeResize() {
    if (resizeTimerID == null) {
        resizeTimerID = window.setTimeout(function() {
            resizeTimerID = null;
            tl.layout();
        }, 500);
    }
}
