<?php
class UNL_UCBCN_Manager_Feature
{
    /**
     * Management interface to build a featured page for.
     *
     * @var UNL_UCBCN_Manager
     */
    public $manager;
    
    /**
     * Events to build a featured form for.
     *
     * @var array of UNL_UCBCN_Event
     */
    public $events;
    
    
    public function __construct(UNL_UCBCN_Manager &$manager, $events)
    {
        $this->manager   = $manager;
	$this->events 	 = $events;        

        if (count($events) > 0) {
            $submitted = false;
            $db = $this->manager->user->getDatabaseConnection();
            foreach ($this->events as $event) { 	    
	        if (isset($_POST['featureevent'.$event->id])) {
			$submitted = true;
			$query = false; 
			if ($_POST['featureevent'.$event->id] == '0'){
			    $status = 'NULL';
			    $query = true;
			}
			elseif ($_POST['featureevent'.$event->id] == '1'){
			    $status = "'featured'";
			    $query = true;
			}
			if ($query) {
			    $sql = "UPDATE event SET status = $status WHERE id = " . $event->id;
			    $db->query($sql);
			}
		}
                if (isset($_POST['homepageevent'.$event->id])) {
                    $submitted = true;
                    $query = false;
                    if ($_POST['homepageevent'.$event->id] == '0'){
                        $homepage = '0';
                        $query = true;
                    }
                    elseif ($_POST['homepageevent'.$event->id] == '1'){
                        $homepage = '1';
                        $query = true;
                    }
                    if ($query) {
                        $sql = "UPDATE event SET homepage = $homepage WHERE id = " . $event->id;
                        $db->query($sql);
                    }
                }
	    }
	    if ($submitted) {
                // We have processed the events. Redirect.
                $this->manager->localRedirect($this->manager->uri . '?list=posted');
                exit();
            }
        } else {
            return new UNL_UCBCN_Error('No events selected!');
        }
    }    
}
?>
