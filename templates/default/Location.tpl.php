<div class="location">
<?php
if (isset($this->mapurl)) {
    echo '<a class="mapurl" href="'.UNL_UCBCN_Frontend::dbStringToHtml($this->mapurl).'">'.UNL_UCBCN_Frontend::dbStringToHtml($this->name).'</a>';
} else {
    echo UNL_UCBCN_Frontend::dbStringToHtml($this->name);
}

if (isset($this->streetaddress1)) {
    echo '<div class="adr">';
    echo '<span class="street-address">'.UNL_UCBCN_Frontend::dbStringToHtml($this->streetaddress1).'<br />'.UNL_UCBCN_Frontend::dbStringToHtml($this->streetaddress2).'</span>';
    if (isset($this->city)) {
        echo '<span class="locality">'.UNL_UCBCN_Frontend::dbStringToHtml($this->city).'</span>';
    }
    if (isset($this->state)) {
        echo ' <span class="region">'.UNL_UCBCN_Frontend::dbStringToHtml($this->state).'</span>';
    }
    if (isset($this->zip)) {
        echo ' <span class="postal-code">'.UNL_UCBCN_Frontend::dbStringToHtml($this->zip).'</span>';
    }
    echo '</div>';
}
if (isset($this->directions)) {
    echo '<div class="directions">Directions: '.UNL_UCBCN_Frontend::dbStringToHtml($this->directions).'</div>';
}
if (isset($this->additionalpublicinfo)) {
    echo '<div class="additionalinfo">Additional Info: '.UNL_UCBCN_Frontend::dbStringToHtml($this->additionalpublicinfo).'</div>';
}
?>
</div>