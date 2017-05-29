<?php

use Cake\ORM\TableRegistry;

?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Debug links') ?></li>
        <li><?= $this->Html->link(__('View Configured Routes'), '/debug/routes') ?></li>
        <li><?= $this->Html->link(__('View Authorisations'), '/debug/auth') ?></li>
        <li><?= $this->Html->link(__('Create DB Password Hash'), '/debug/hash') ?></li>
        <li><?= $this->Html->link(__('Account Login / Logout'), '/debug/login') ?></li>
        <li><?= $this->Html->link(__('Set Player Password'), '/debug/password') ?></li>
        <li><?= $this->Html->link(__('Set Authorisation'), '/debug/role') ?></li>
    </ul>
</nav>
<div class="players index large-9 medium-8 columns content">
<?php

function setPassword($plin, $password) {
	$table = TableRegistry::get('Players');
	$player = $table->findById($plin)->first();
	if(is_null($player))
		return "No player found with plin $plin";

	$player->password = $password;
	$table->save($player);

	$errors = $player->errors('password');
	if(!empty($errors))
		return $errors[0];

	return "Player #$plin password set.";
}

if(is_null($user)) {
	echo "<h3>You need to be logged in!</h3>";
	echo "<p>".$this->Html->link('Click here to login.', '/debug/login')."</p>";
	echo "</div>";
	return;
} else if($user['role'] != 'Super') {
	echo "<h3>Sorry, you are not authorized.</h3>";
	echo "<p>Auth level 'Super' is required, you have '".$user['role']."'.</p>";
	echo "</div>";
	return;
}

$msg = '&nbsp;';
$plin = $this->request->data('plin');
$pass = $this->request->data('password');

if(!empty($plin) && !empty($pass)) {
	$msg = setPassword($plin, $pass);
}

echo "<h3>Set Player Password</h3>";
echo $this->Form->create();
echo $this->Form->input('plin'
		, ['label' => 'Plin', 'type' => 'text']);
echo $this->Form->input('password'
		, ['label' => 'Password', 'type' => 'password']);
echo "<p>$msg</p>";
echo $this->Form->button(__('Set'));

?>
</div>
