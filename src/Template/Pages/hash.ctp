<?php
use Cake\Auth\DefaultPasswordHasher;

?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
</nav>
<div class="players index large-9 medium-8 columns content">
    <h3><?= __('Create database password hash') ?></h3>
<?php
	$hasher = new DefaultPasswordHasher();
	$pass = $this->request->query('password');
	$hash = $hasher->hash($this->request->query('password'));

	echo $this->Form->create(null, ['method' => 'GET']);
	echo $this->Form->input('password'
			, ['label' => 'Password', 'type' => 'text', 'value' => $pass]);
	echo $this->Form->input(NULL
			, ['label' => 'Hash', 'type' => 'text', 'value' => $hash]);
	echo $this->Form->button(__('Generate'));
?>
</div>
