<p>Please log in using your HSU User Name and Password.</p>

<?php
	$form = new HTML_QuickForm('event_login', 'post', 'index.php');
	$form->addElement('text',$this->user_field,'HSU User Name');
	$form->addElement('password',$this->password_field,'Password');
	$form->addElement('xbutton','submit','Submit','type="submit"');
	$renderer = new HTML_QuickForm_Renderer_Tableless();
	$form->accept($renderer);
	echo $renderer->toHtml();
?>
