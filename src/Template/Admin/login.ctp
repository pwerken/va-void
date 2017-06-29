<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Debug links') ?></li>
<?php
foreach($links as $url => $descr)
	echo '<li>'.$this->Html->link($descr, $url)."</il>\n";
?>
    </ul>
</nav>
<div class="players index large-9 medium-8 columns content">
    <h3>Authentication</h3>
<?php

if($user) {
	echo "<p>Logged in as: ".$user['full_name']."<br/>";
	echo "With auth level: ".$user['role']."</p>";
	echo $this->Html->link(__('Click here to logout.'), '/admin/logout');
} else {
	echo $this->Form->create('', ['url' => '/admin/login']);
	echo "<fieldset>\n";
	echo $this->Form->input('id', ['label' => 'Plin', 'type' => 'text']);
	echo $this->Form->input('password', ['type'=>'password']);
	echo $this->Form->button(__('Login'));
	echo "</fieldset>\n";
	echo $this->Form->end();
}

?>
</div>
