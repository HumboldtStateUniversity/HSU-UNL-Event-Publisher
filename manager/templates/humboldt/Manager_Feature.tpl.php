<h3>Feature <span class="title">'<?php echo $this->events[0]->title; ?>'</span></h3>
<form action="<?php echo $this->manager->uri; ?>?action=feature" method="post">
<?php

require_once 'HTML/Table.php';
$t = new HTML_Table(array('id'=>'feature_cals'));
$t->addRow(array('Event','Featured', 'HSU Homepage'), null, 'TH');

foreach ($this->events as $event) {

    if ($event->status == 'featured') {
        $featuredcheck = 'checked = checked';
    } else {
	$featuredcheck = NULL;
    }

    $featuredhidden = HTML_QuickForm::createElement('hidden', 'featureevent'.$event->id, 0);
    $featuredhidden = $featuredhidden->toHtml();

    $featured = HTML_QuickForm::createElement('checkbox','featureevent'.$event->id, null, null, $featuredcheck);
    $featured = $featured->toHtml();

    $homepagehidden = HTML_QuickForm::createElement('hidden', 'homepageevent'.$event->id, 0);
    $homepagehidden = $homepagehidden->toHtml();

    $homepage = HTML_QuickForm::createElement('checkbox','homepageevent'.$event->id, null, null);
    $homepage = $homepage->toHtml();

    $t->addRow(array($event->title, $featuredhidden.$featured, $homepagehidden.$homepage));

}
echo $t->toHtml();

?>
<input type="submit" value="Go" />
</form>
<script type="text/javascript">
try {stripe('recommend_cals');} catch(e) {}
</script>
