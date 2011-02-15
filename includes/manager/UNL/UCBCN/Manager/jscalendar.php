<?php

require_once 'HTML/QuickForm/element.php';

HTML_QuickForm::registerElementType('jscalendar', 'UNL/UCBCN/Manager/jscalendar.php', 'HTML_QuickForm_jscalendar');

/**
 * HTML class for a jscalendar field
 *
 * jscalendar is "The coolest DHTML/JavaScript Calendar" which can be obtained
 * from http://dynarch.com/projects/calendar
 * 
 * @author Jan Wagner <wagner@netsols.de>
 * @license LGPL
 * @copyright Copyright &copy; 2006, Jan Wagner <wagner@netsols.de>
 */
class HTML_QuickForm_jscalendar extends HTML_QuickForm_element
{
    /**
     * Name of the element
     * 
     * @var string
     * @access protected
     */
    var $_name = '';

    /**
     * Options should contain:
     * 
     * baseURL: where to find the jscalendar files (with trailing /)
     * styleCss: name of style css file
     * language: Language code
     * setup: A hash of options to pass to Calendar.setup
     * 
     * @var array
     * @access protected
     */
    var $_options;
    
    /**
     * Just the constructor
     * 
     * @access public
     * @param string elementName
     * @param string elementLabel
     * @param array options Array of element option
     * @param array attributes HTML attributes
     * @return void
     */
    function HTML_QuickForm_jscalendar($elementName = null, $elementLabel = null, $options = array(), $attributes = null)
    {
        $this->HTML_QuickForm_element($elementName, $elementLabel, $attributes);
        $this->_type = 'jscalendar';
        
        if (is_array($options)) {
            $this->_options = $options;
        }
    }
    
    /**
     * Function that renders the element into HTML
     * 
     * @access public
     * @return string HTML output
     */
    function toHtml()
    {
        if ($this->_flagFrozen) {
            return '';
        }
        
        $html = '';
        
        if (!defined('HTML_QUICKFORM_JSCALENDAR_LOADED')) {
            $html = sprintf(
                '<style type="text/css">@import url(%s);</style>' .
                '<script type="text/javascript" src="%scalendar.js"></script>' .
                '<script type="text/javascript" src="%slang/calendar-%s.js"></script>' .
                '<script type="text/javascript" src="%scalendar-setup.js"></script>',              
                $this->_options['baseURL'] . $this->_options['styleCss'],
                $this->_options['baseURL'],
                $this->_options['baseURL'], $this->_options['language'],
                $this->_options['baseURL']
            );
            define('HTML_QUICKFORM_JSCALENDAR_LOADED', true);
        }
        
        if (isset($this->_options['image'])) {
            if (!isset($this->_options['image']['id'])) {
                $name = $this->getName('name');
                $this->_options['image']['id'] = $name . '_trigger';
                $this->_options['setup']['button'] = $name . '_trigger';
            }
            $html .= '<a href="javascript:void(null);"><img ';
            foreach ($this->_options['image'] as $k => $v) {
                $html .= sprintf('%s="%s" ', $k, $v);
            }
            $html .= '/></a>';
        }
        
        $html .= '<script type="text/javascript">';
        $html .= 'Calendar.setup(';
        $html .= $this->_array2JS($this->_options['setup']);
        $html .= ');';
        $html .= '</script>';

        return $html;
    }
    
    /**
     * Set the name of the element
     * 
     * @access public
     * @param string name Name of the element
     * @return void
     */
    function setName($name)
    {
        $this->_name = $name;
    }
    
    /**
     * Returns the name of the element
     * 
     * @access public
     * @return string Name of the element
     */
    function getName()
    {
        return $this->_name;
    }
    
    /**
     * Helper function that translates a php var into a JavaScript value
     * 
     * @access protected
     * @param mixed var Any type of variable
     * @param boolean quote Should a string be quoted
     * @return mixed JavaScript value of the variable
     */
    function _var2JS($var, $quote = true)
    {
        $type = gettype($var);        
        if ($type == "array") {
            $value = $this->_array2JS($var);
        } elseif ($type == "boolean") {
            $value = ($var) ? 'true' : 'false';
        } elseif ($type == "integer" || $type == "double") {
            $value = $var;
        } else {
            $value = ($quote) ? "'$var'" : $var;
        }
        return $value;
    }

    /**
     * Return JavaSript representation of an array (hash and indexed)
     *
     * @access protected
     * @return string  
     */
    function _array2JS($value)
    {
        $hash = true;
        if (is_array($value)) {
            $key = key($value);
            if (is_numeric($key) && $key === 0) {
                $hash = false;
            }
            reset($value);
        }
        
        $js  = ($hash) ? '{ ' : '[ ';
        
        $i = 1;
        $j = count($value);
        foreach ($value as $k => $v) {
            $quote = true;
            if ($hash) {
                $js .= "'$k' : ";
                // workaround for function callbacks (sf.net patch #1441561)
                switch($k) {
                    case 'disableFunc':
                    case 'dateStatusFunc':
                    case 'flatCallback':
                    case 'onSelect':
                    case 'onClose':
                    case 'onUpdate':
                        $quote = false;
                        break;
                }
            }
            $js .= $this->_var2JS($v, $quote);
            if ($i++ < $j) {
                $js .= ', ';
            }
        }
        $js .= ($hash) ? ' }' : ' ]';

        return $js;
    }
}

?>