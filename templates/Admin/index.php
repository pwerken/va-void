<?php
declare(strict_types=1);

?>
<h3>VA - VOID</h2>
<a href="http://www.the-vortex.nl">Vortex Adventures</a> - <b>V</b>ortex <b>O</b>nline <b>I</b>ncharacter <b>D</b>atabase

<hr>
<?php

if(isset($user)) {
	echo "<p>Logged in as: ".$user['full_name']."<br/>";
	echo "With auth level: ".$user['role']."</p>";
	echo $this->Html->link(__('Click here to logout.'), '/admin/logout');
	return;
}

?>
<h3>Social media login</h3>
<fieldset>
<?php

foreach($socials as $social)
{
    $img = $this->Html->image("social-auth/$social.png",
        [ 'alt' => $social, 'class' => 'action-link' ]);
    echo $this->Form->postLink($img,
        [ 'prefix' => false
        , 'controller' => 'Admin'
        , 'action' => 'social'
        , $social
        ],
        [ 'escape' => false
        , 'data' => ['redirect' => $redirect]
        ]);
    echo '&nbsp;&nbsp;&nbsp;&nbsp;';
}
?>
<br/><br/>
<em>Requires that your VOID account has the same email adres as used with the service.</em>
</fieldset>
<hr>
<h3>Legacy password login</h3>
<?= $this->Form->create(); ?>
<fieldset>
	<?=$this->Form->control('id', ['label' => 'Plin', 'type' => 'text']); ?>
	<?=$this->Form->control('password', ['type'=>'password']); ?>
	<?=$this->Form->button(__('Login')); ?>
<br/>
<em>Requires that a password is set for your VOID account.</em>
</fieldset>
<?= $this->Form->end(); ?>
