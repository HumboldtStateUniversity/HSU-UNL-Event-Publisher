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
    //public $events;
    
    
    public function __construct(UNL_UCBCN_Manager &$manager, $events)
    {
        $this->manager   = $manager;
        
        if (count($events) > 0) {
            $db = $this->manager->user->getDatabaseConnection();
            foreach ($events as $event) {
                $sql = "UPDATE event " . 
                        "SET status = 'featured' " .
                        "WHERE id = " . $event->id;
                $db->query($sql);
            }
        } else {
            return new UNL_UCBCN_Error('No events selected!');
        }
        
        //var_dump($this->manager->calendar->id);
        $this->manager->localRedirect($this->manager->uri . '?list=posted');
        exit();
    }    
}
?>
