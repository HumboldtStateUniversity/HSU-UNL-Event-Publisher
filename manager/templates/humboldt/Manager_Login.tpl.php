<div id="loginwrap">
<div class="eventdirections">
	<h2>Before you begin, make sure your event meets these requirements:</h2>
	<ul>
		<li>The event must be sponsored by Humboldt State University, department, program, student club or organization.</li>
		<li>The event listing must include an event title, date, time, location, contact person, and admission fee (if applicable).</li>
		<li>Events will be moderated by HSU staff</li>
		<li>Don't meet the requirements? <a href="http://humboldt.edu/marcom/campusResources.php?section=eventpromo">Learn more about promoting your event</a></li>
	</ul>         	
</div>
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
</div>