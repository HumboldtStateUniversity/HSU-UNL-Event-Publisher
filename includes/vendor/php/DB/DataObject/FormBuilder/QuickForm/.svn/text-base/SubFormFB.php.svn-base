<?php
/**
 * This is an HTML_QuickForm element for internal FormBuilder use only. It
 * creates a select element for a foreign key / link which also has a hidden
 * div with a form for a new linked record in it. All processing is done in
 * the FormBuilder main class.
 *
 * PHP Versions 4 and 5
 *
 * @category DB
 * @package  DB_DataObject_FormBuilder
 * @copyright  1997-2006 The PHP Group
 * @license    http://www.gnu.org/licenses/lgpl.txt LGPL 2.1
 * @author   Justin Patrin <papercrane@reversefold.com>
 * @version  $Id: SubFormFB.php 225313 2006-12-19 19:13:11Z justinpatrin $
 */

require_once('DB/DataObject/FormBuilder/QuickForm/SubForm.php');

class HTML_QuickForm_SubFormFB extends HTML_QuickForm_SubForm {
    
    function HTML_QuickForm_SubFormFB($name=null, $label=null, $form=null)
    {
        $this->HTML_QuickForm_SubForm($name, $label, $form);
    }

    function preValidationCallback($values) {
        return isset($values[$this->getName().'__displayed']) && $values[$this->getName().'__displayed'];
    }

    function toHtml() {
        return '
<script language="javascript" type="text/javascript">
function db_do_fb_'.$this->getName().'_display(sel) {
  div = document.getElementById("'.$this->getName().'__div");
  if(sel.value == "'.$this->linkNewValueText.'") {
    div.style.visibility = "visible";
    div.style.display = "block";
    div.style.overflow = "auto";
    document.getElementById("'.$this->getName().'__displayed").value = "1";
  } else {
    div.style.display = "none";
    div.style.overflow = "hidden";
    div.style.visibility = "hidden";
    document.getElementById("'.$this->getName().'__displayed").value = "0";
  }
}
</script>
<div id="'.$this->getName().'__div">
'.parent::toHtml().'
</div>
<script language="javascript">
db_do_fb_'.$this->getName().'_display(document.getElementById("'.$this->selectName.'"));
</script>
';
    }
}

if (class_exists('HTML_QuickForm')) {
    HTML_QuickForm::registerElementType('subFormFB', __FILE__, 'HTML_QuickForm_SubFormFB');
}

?>