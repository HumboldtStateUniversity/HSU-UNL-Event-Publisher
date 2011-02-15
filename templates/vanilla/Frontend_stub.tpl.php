        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->uri; ?>templates/vanilla/css/fullcalendar.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/themes/<?php echo $this->calendar->theme ?>/jquery-ui.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
        <script src="<?php echo $this->uri; ?>templates/vanilla/javascript/fullcalendar.min.js"></script>

        <!-- Main output for the view determined by determineView() and populated with run() -->
        <?php UNL_UCBCN::displayRegion($this->output); ?>
        <div>
            <ul>
                <li><a href="<?php echo UNL_UCBCN_Frontend::formatURL(array('calendar'=>$this->calendar->id,'upcoming'=>'upcoming','format'=>'rss')); ?>&amp;limit=100" title="RSS feed">Calendar RSS feed</a></li>
                <li><a href="<?php echo UNL_UCBCN_Frontend::formatURL(array('calendar'=>$this->calendar->id,'upcoming'=>'upcoming','format'=>'ics')); ?>&amp;limit=100" title=".ical format">Calendar in .ical format</a></li>
            </ul>
        </div>