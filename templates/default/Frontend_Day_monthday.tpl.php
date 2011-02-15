<a href="<?php echo $this->url; ?>"><?php echo $this->day; ?></a><span class="monthvalue_ID"><?php echo $this->month; ?></span>
<?php
    UNL_UCBCN::outputTemplate('UNL_UCBCN_EventListing','EventListing_month');
    UNL_UCBCN::displayRegion($this->output);
?>