<?php
/**
 * This is a driver class for the DB_DataObject_FormBuilder package.
 * It uses HTML_QuickForm to render the forms, it extends the default
 * QuickForm driver to override the create subform element.
 *
 */

require_once ('DB/DataObject/FormBuilder/QuickForm.php');

/**
 * This is a driver class for the DB_DataObject_FormBuilder package.
 * It uses HTML_QuickForm to render the forms.
 */
class DB_DataObject_FormBuilder_UCBCN_QuickForm extends DB_DataObject_FormBuilder_QuickForm
{
    
    /**
     * DB_DataObject_FormBuilder_QuickForm::_createSubForm()
     * 
     * Returns a QuickForm element for a SubForm.
     * Used in _generateForm().
     *
     * @param string    $fieldName  The field name to use for the QuickForm element
     * @param string    $label      The label to use for the QuickForm element
     * @param           $subForm    HTML_QuickForm element
     * @return object               The HTML_QuickForm_element object.
     * @see DB_DataObject_FormBuilder::_generateForm()
     */
     function &_createSubForm($fieldName, $label, &$subForm)
     {
        require_once('UNL/UCBCN/Manager/SubForm.php');
        $element =& HTML_QuickForm::createElement('subForm',
                                                  $fieldName,
                                                  $label,
                                                  $subForm);
        return $element;
     }
}

if (class_exists('HTML_QuickForm')) {
    HTML_QuickForm::registerElementType('subForm', __FILE__, 'UNL_UCBCN_Manager_SubForm');
}

?>