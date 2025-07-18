<?php
declare(strict_types=1);
/**
 * @var \Cake\View\View $this
 */
?>
<h3>Legacy password authentication</h3>
<p>
If the password field is left blank, the password is removed.<br/>
</p>
<?php

echo $this->Form->create();
echo $this->Form->control(
    'plin',
    ['label' => 'Plin', 'type' => 'text', 'value' => ''],
);
echo $this->Form->control(
    'password',
    ['label' => 'Password', 'type' => 'password', 'value' => ''],
);
echo $this->Form->button(__('Set password'));
echo $this->Form->end();
