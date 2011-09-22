<div class="day_cal">
<?php
if (is_a($this->output,'UNL_UCBCN_EventListing')) {
    if ($dt = strtotime($this->query)) {
        echo '<h2 class="results">Results for events dated <span>'.date('F jS',$dt).'</span></h2>';
    } elseif (isset($this->eventtype)) {
        echo '<h2 class="results">Results for <span>' . $this->eventtype_name[0] . '</span></h2>';
    } else {
        echo '<h2 class="results">Results for "<span>'.htmlentities($this->query).'</span>"</h2>';
    }
    echo '<h3 class="result-count">'.count($this->output->events).' results</h3>';
}
UNL_UCBCN::displayRegion($this->output);

?>

</div>
</div>
