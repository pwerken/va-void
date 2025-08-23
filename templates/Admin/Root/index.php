<?php
declare(strict_types=1);
/**
 * @var \App\View\AdminView $this
 * @var array $socials
 * @var string $redirect
 */
?>
<h3>VA - VOID</h3>
<a href="http://www.the-vortex.nl">Vortex Adventures</a>
-
<b>V</b>ortex <b>O</b>nline <b>I</b>ncharacter <b>D</b>atabase

<hr>
<?php

if (isset($user)) {
    echo '<p>Logged in as: ' . $user['name'] . '<br/>';
    echo 'With auth level: ' . $user['role']->label() . '</p>';
    echo $this->Html->link(__('Click here to logout.'), ['controller' => 'Logout']);

    return;
}

?>
<h3>Login with:</h3>
<?php
foreach ($socials as $social) {
    $img = $this->Html->image(
        "social-auth/$social.svg",
        [
            'alt' => $social,
            'class' => 'action-link' ,
            'style' => 'width: 64px; height: 64px; margin: 16px;',
        ],
    );
    echo $this->Form->postLink(
        $img,
        [ 'controller' => 'Social', 'action' => 'login', $social ],
        [ 'escape' => false, 'data' => ['redirect' => $redirect ] ],
    ) . "\n";
}
?>
<br/>
<em>Requires that your VOID account has the same email adres as used with the service.</em>
<hr>
<h3>Legacy password:</h3>
<?= $this->Form->create() ?>
<?= $this->Form->control('id', [
        'label' => 'Plin',
        'name' => 'plin',
        'class' => 'plin',
        'type' => 'number',
        'value' => '',
        'min' => 0,
        'max' => 9999,
        'maxlength' => 4,
        ]) ?>
<?= $this->Form->control('password', ['type' => 'password']) ?>
<?= $this->Form->button(__('Login')) ?>
<br/>
<em>Requires that a password is set for your VOID account.</em>
<?= $this->Form->end() ?>
