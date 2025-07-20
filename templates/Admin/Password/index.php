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

echo $this->Form->create()
    . $this->Form->control(
        'plin',
        ['label' => 'Plin', 'type' => 'text', 'value' => '', 'class' => 'plin'],
    )
    . '<br class="on-mobile"/>'
    . $this->Form->control(
        'password',
        ['label' => 'Password', 'type' => 'password', 'value' => ''],
    )
    . '<br class="on-mobile"/>'
    . $this->Form->button(__('Set password'))
    . $this->Form->end();
