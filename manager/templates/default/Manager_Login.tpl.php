<div class="login-left"><span>UNL's</span> Event Publishing System</div>
<div class="login-right">
<p>Please log in using your My.UNL (Blackboard/LDAP) Username and Password.</p>

<?php
	$form = new HTML_QuickForm('event_login');
	$form->addElement('text',$this->user_field,'User');
	$form->addElement('password',$this->password_field,'Password');
	$form->addElement('xbutton','submit','Submit','type="submit"');
	$form->addElement('static','','','<a href="http://my.unl.edu/webapps/blackboard/password" title="" id="forgot">(Forgot your password?)</a>');
	$renderer = new HTML_QuickForm_Renderer_Tableless();
	$form->accept($renderer);
	echo $renderer->toHtml();
?>
</div>