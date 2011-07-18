<div class="login">
<p>Please log in using your HSU User Name and Password.</p>

<?php
	$attributes = NULL;

        if ($this->status == -3){
            echo "<p class='error'>Your HSU User Name and/or HSU Password were incorrect.</p>";
	    $attributes = 'class="error"';
        }

	$form = new HTML_QuickForm('event_login', 'post', 'index.php');
        $form->addElement('text',$this->user_field,'HSU User Name', $attributes);
	$form->addElement('password',$this->password_field,'Password', $attributes);
	$form->addElement('xbutton','submit','Submit','type="submit"');
	$renderer = new HTML_QuickForm_Renderer_Tableless();
	$form->accept($renderer);
	echo $renderer->toHtml();
?>
</div>
