<h3>Authentication</h3>
<?php

echo $this->Form->create();
echo $this->Form->control('plin'
		, ['label' => 'Plin', 'type' => 'text']);
echo $this->Form->control('password'
		, ['label' => 'Password', 'type' => 'password']);
echo $this->Form->button(__('Set password'));

