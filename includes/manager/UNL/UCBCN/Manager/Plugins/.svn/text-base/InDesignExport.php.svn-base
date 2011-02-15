<?php
/**
 * This is a plugin for the management interface to allow calendar data to
 * be exported as Adobe InDesign Tags.
 * 
 * @author Brett Bieber
 * @license BSD
 * @date 2006-08-30
 */
require_once 'UNL/UCBCN/Manager/Plugin.php';
require_once 'HTML/QuickForm.php';

class UNL_UCBCN_Manager_InDesignExport extends UNL_UCBCN_Manager_Plugin
{
    public $name    = 'InDesign Tags Export';
    public $version = '0.0.1';
    public $author  = 'Brett Bieber';
    
    public $manager;
    public $uri;
    public $output = array();
    
    public function startup(&$manager,$uri) {
        $this->manager =& $manager;
        $this->uri        = $uri;
        if (isset($_GET['starttime']) && isset($_GET['endtime'])) {
            if (strtotime($_GET['starttime']) !== false
             && strtotime($_GET['endtime']) !== false) {
                    $this->doExport();
            } else {
                $this->output[] = '<p>Please change your start date and end date to a recognizeable format.</p>';
            }
        }
    }

    public function run()
    {
        $this->output[] = '<p class="sec_main">Adobe InDesign Tags Exporter</p>' .
                '<p>End users can use this tool to export events in Adobe InDesign Tags formatted text.</p>';
        if (isset($_GET['_id_export'])) {
            $action = $_GET['id_export'];
        } else {
            $action = 'start';
        }
        switch($action)
        {
            case 'start':
            default:
                $this->output[] = $this->showChooseDateForm();
        }
    }
    
    public function showChooseDateForm()
    {
        $form = new HTML_QuickForm('indesign_export','get',$this->uri);
        $form->addElement('hidden','action','plugin');
        $form->addElement('hidden','p','UNL_UCBCN_Manager_InDesignExport');
        $form->addElement('text','starttime','Start Date');
        $form->addElement('text','endtime','End Date');
        $form->addElement('submit','submit','Submit');
        
        return $form->toHtml();
    }
    
    public function doExport()
    {
        $mdb2 = $this->manager->getDatabaseConnection();
        $res = $mdb2->query('SELECT event.title as title, ' .
                            'event.subtitle as subtitle, ' .
                            'event.description as description, ' .
                            'UNIX_TIMESTAMP( eventdatetime.starttime ) AS epoch, ' .
                            'location.name as location,' .
                            'event.listingcontactphone as phone ' .
                'FROM event,calendar_has_event,eventdatetime,location ' .
                'WHERE event.id = calendar_has_event.event_id AND ' .
                'eventdatetime.location_id = location.id AND ' .
                'eventdatetime.event_id = event.id AND ' .
                'calendar_has_event.calendar_id='.$this->manager->calendar->id.' AND ' .
                'eventdatetime.starttime>=\''.date('Y-m-d H:i:s',strtotime($_GET['starttime'])).'\' AND ' .
                'eventdatetime.starttime<=\''.date('Y-m-d H:i:s',strtotime($_GET['endtime'])).'\' ' .
                'ORDER BY eventdatetime.starttime ASC;');
        if ($res->numRows()>0) {
            header('Content-type: text/plain');
            header('Content-Disposition: attachment; filename="idtags.txt"');
            echo "<ASCII-MAC>\r<vsn:3.000000><fset:InDesign-Roman><ctable:=<Black:COLOR:CMYK:Process:0.000000,0.000000,0.000000,1.000000>>";
            while ($row = $res->fetchRow(MDB2_FETCHMODE_ASSOC)) {
                echo "\r".date('M\. j\, ',$row['epoch']).str_replace(array('<i>','</i>'),array('<ct:Italic>','<ct:>'),$row['title']);
                if (isset($row['subtitle'])) {
                    echo ' - '.$row['subtitle'];
                }
                if (isset($row['description'])) {
                    echo ' - '.$row['description'];
                }
                if (date('Hi',$row['epoch'])!='0000') {
                    if (date('i',$row['epoch'])!='00') {
                        echo date(' g:i a,',$row['epoch']);
                    } else {
                        echo date(' g a,',$row['epoch']);
                    }
                }
                echo ' '.$row['location'].'.';
                if (!empty($row['phone'])) {
                    echo ' Call '.$row['phone'].'.';
                }
            }
            exit();
        } else {
            $this->output[] = '<p>No events could be found between those two dates.</p>';
        }
    }
}

UNL_UCBCN_Manager::registerPlugin('UNL_UCBCN_Manager_InDesignExport');
?>