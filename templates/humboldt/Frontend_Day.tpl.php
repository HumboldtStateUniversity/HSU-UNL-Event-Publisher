<div class="calendar"></div>
<div class="day_cal">
<h4 class="sec_main">
<?php
$day = new Calendar_Day($this->year,$this->month,$this->day);
echo date('l, F jS',$day->getTimeStamp());
?> <a class="permalink" href="<?php echo $this->url; ?>">(link)</a>
</h4>
<p id="day_nav">
<?php
    $prev = $day->prevDay('object');
    echo '<a class="url prev" href="'.UNL_UCBCN_Frontend::formatURL(array(    'd'=>$prev->thisDay(),
                                                            'm'=>$prev->thisMonth(),
                                                            'y'=>$prev->thisYear(),
                                                            'calendar'=>$this->calendar->id)).'">Previous Day</a> ';
    $next = $day->nextDay('object');
    echo '<a class="url next" href="'.UNL_UCBCN_Frontend::formatURL(array(    'd'=>$next->thisDay(),
                                                            'm'=>$next->thisMonth(),
                                                            'y'=>$next->thisYear(),
                                                            'calendar'=>$this->calendar->id)).'">Next Day</a></p>';

    UNL_UCBCN::displayRegion($this->output);
    echo '<p id="feeds">
            <a id="icsformat" title="ics format for events on '.date('l, F jS',$day->getTimeStamp()).'" href="'.UNL_UCBCN_Frontend::reformatURL($this->url,array('format'=>'ics')).'">ics format for events on '.date('l, F jS',$day->getTimeStamp()).'</a>
            <a id="rssformat" title="rss format for events on '.date('l, F jS',$day->getTimeStamp()).'" href="'.UNL_UCBCN_Frontend::reformatURL($this->url,array('format'=>'rss')).'">rss format for events on '.date('l, F jS',$day->getTimeStamp()).'</a>
            </p>'; ?>
</div>
<script type='text/javascript'> 
	$(document).ready(function() {
		$.extend({
	        getUrlVars: function(){
	          var vars = [], hash;
	          var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	          for(var i = 0; i < hashes.length; i++)
	          {
	            hash = hashes[i].split('=');
	            vars.push(hash[0]);
	            vars[hash[0]] = hash[1];
	          }
	          return vars;
	        },
	        getUrlVar: function(name){
	          return $.getUrlVars()[name];
	        }
      });
	$('.calendar').fullCalendar({
		theme: true,
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		editable: false,
		defaultView: 'agendaDay',
		events: '?upcoming=upcoming&limit=100&format=json',
		date: $.getUrlVar('d')
	});
});
</script> 