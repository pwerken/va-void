<?php
declare(strict_types=1);
/**
 * @var \App\View\AdminView $this
 */
?>
<h3>Legacy Authentication</h3>
<p>
If the password field is left blank, the password is removed.<br/>
</p>
<?php

echo $this->Form->create(null, ['url' => ['controller' => 'Password', 'action' => 'edit']])
    . $this->Form->control('plin', [
        'label' => 'Plin',
        'class' => 'plin',
        'type' => 'number',
        'value' => '',
        'min' => 0,
        'max' => 9999,
        'maxlength' => 4,
        ])
    . '<br class="on-mobile"/>'
    . $this->Form->control(
        'password',
        ['label' => 'Password', 'type' => 'password', 'value' => ''],
    )
    . '<br class="on-mobile"/>'
    . $this->Form->button('Set')
    . $this->Form->end();
