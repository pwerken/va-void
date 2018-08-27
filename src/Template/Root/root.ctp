<h3>VA - VOID</h2>
<a href="http://www.the-vortex.nl">Vortex Adventures</a> - <b>V</b>ortex <b>O</b>nline <b>I</b>ncharacter <b>D</b>atabase

<hr>
<?php

if(isset($user)) {
	echo "<p>Logged in as: ".$user['full_name']."<br/>";
	echo "With auth level: ".$user['role']."</p>";
	echo $this->Html->link(__('Click here to logout.'), '/admin/logout');
} else {
	echo "<h3>Login</h3>";
	echo $this->Form->create(null, ['url' => '/admin']);
	echo "<fieldset>\n";
	echo $this->Form->control('id', ['label' => 'Plin', 'type' => 'text']);
	echo $this->Form->control('password', ['type'=>'password']);
	echo $this->Form->button(__('Login'));
	echo "</fieldset>\n";
	echo $this->Form->end();
}
