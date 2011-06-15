<h3>Feature <span class="title">'<?php echo $this->events[0]->title; ?>'</span></h3>
<form action="<?php echo $this->manager->uri; ?>?action=feature" method="post">
<?php

require_once 'HTML/Table.php';
$t = new HTML_Table(array('id'=>'feature_cals'));
$t->addRow(array('Event','Not Featured', 'Featured'), null, 'TH');

foreach ($this->events as $event) {

    if ($event->status == 'featured') {
        $isfeaturedcheck = 'checked = checked';
        $notfeaturedcheck = NULL;
    } else {
	$isfeaturedcheck = NULL;
	$notfeaturedcheck = 'checked = checked';
    }

    $notfeatured = HTML_QuickForm::createElement('radio','changeevent'.$event->id,null,null, 'Not Featured', $notfeaturedcheck);
    $notfeatured = $notfeatured->toHtml();

    $featured = HTML_QuickForm::createElement('radio','changeevent'.$event->id,null,null, 'Featured', $isfeaturedcheck);
    $featured = $featured->toHtml();
    $t->addRow(array($event->title, $notfeatured, $featured));

}
echo $t->toHtml();

?>
<input type="submit" value="Go" />
</form>
<script type="text/javascript">
try {stripe('recommend_cals');} catch(e) {}
</script>
