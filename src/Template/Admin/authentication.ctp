<h3>Authentication</h3>
<?php

echo $this->Form->create();
echo $this->Form->input('plin'
		, ['label' => 'Plin', 'type' => 'text']);
echo $this->Form->input('password'
		, ['label' => 'Password', 'type' => 'password']);
echo $this->Form->button(__('Set'));

