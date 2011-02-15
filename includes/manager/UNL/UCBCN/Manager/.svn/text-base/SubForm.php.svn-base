<?php
/**
 * This file extends the subform class to override the toHTML issue.
 * 
 * PHP version 5
 * 
 * @category  Events 
 * @package   UNL_UCBCN_Manager
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */

require_once 'DB/DataObject/FormBuilder/QuickForm/SubForm.php';

/**
 * Makes subforms use the correct renderer and do some minor manupulations.
 * 
 * @category Events
 * @package  UNL_UCBCN_Manager
 * @author   Brett Bieber <brett.bieber@gmail.com>
 * @license  http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link     http://pear.unl.edu/
 */
class UNL_UCBCN_Manager_SubForm extends HTML_QuickForm_SubForm
{
    function UNL_UCBCN_Manager_SubForm($name=null, $label=null, $form=null)
    {
        $this->HTML_QuickForm_SubForm($name, $label, $form);
    }

    /**
     * renders the element
     *
     * @return string the HTML for the element
     */
    public function toHtml()
    {
        if (!isset($this->_renderer) || !is_a($this->_renderer, 'HTML_QuickForm_Renderer_Default')) {
            $this->_renderer = clone(HTML_QuickForm::defaultRenderer());
        }
        $this->_renderer->_html =
            $this->_renderer->_hiddenHtml =
            $this->_renderer->_groupTemplate = 
            $this->_renderer->_groupWrap = '';
        $this->_renderer->_groupElements = array();
        $this->_renderer->_inGroup       = false;
        $this->_renderer->setFormTemplate(preg_replace('!</?form[^>]*>!', '', $this->_renderer->_formTemplate));
        $this->_subForm->accept($this->_renderer);
        if (isset($this->_renderer->_fieldsetIsOpen) && $this->_renderer->_fieldsetIsOpen) {
            $this->_renderer->_fieldsetIsOpen = false;
            
            $this->_subForm->accept($this->_renderer);
            
            $html                             = $this->_renderer->toHtml();
            $this->_renderer->_fieldsetIsOpen = true;
            return $html;
        } else {
            return $this->_renderer->toHtml();
        }
    }
}

if (class_exists('HTML_QuickForm')) {
    HTML_QuickForm::registerElementType('subForm', __FILE__, 'UNL_UCBCN_Manager_SubForm');
}

?>