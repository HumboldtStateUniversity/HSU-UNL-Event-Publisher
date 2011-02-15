<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<Events xmlns="urn:cde.berkeley.edu:babl:events:1.00" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:cde.berkeley.edu:babl:events:1.00 UCBCNEvents_1.9.xsd">
<?php
UNL_UCBCN::outputTemplate('UNL_UCBCN_EventInstance','EventInstance_xml');
UNL_UCBCN::outputTemplate('UNL_UCBCN_Frontend_Day','Frontend_Day_xml');
UNL_UCBCN::outputTemplate('UNL_UCBCN_Frontend_Search','Frontend_Day_xml');
UNL_UCBCN::outputTemplate('UNL_UCBCN_Frontend_Upcoming','Frontend_Day_xml');
UNL_UCBCN::outputTemplate('UNL_UCBCN_EventListing','EventListing_icalendar');
UNL_UCBCN::displayRegion($this->output);
?>
</Events>