<?php
declare(strict_types=1);

use Cake\Core\Configure;

?>
<h3>VA - VOID</h2>
<a href="http://www.the-vortex.nl">Vortex Adventures</a> - <b>V</b>ortex <b>O</b>nline <b>I</b>ncharacter <b>D</b>atabase

<hr>
<?php

if(isset($user)) {
	echo "<p>Logged in as: ".$user['full_name']."<br/>";
	echo "With auth level: ".$user['role']."</p>";
	echo $this->Html->link(__('Click here to logout.'), '/admin/logout');
} else {
	echo "<h3>Social media login</h3>";

	$redirect = $this->request->getQuery('redirect') ?? '/admin';

	$socials = ['discord', 'facebook', 'google', 'gitlab'];
	foreach($socials as $social)
	{
		if(!Configure::read("SocialAuth.$social.applicationId"))
			continue;

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

	echo "<hr>";
	echo "<h3>Legacy login</h3>";
	echo $this->Form->create();
	echo "<fieldset>\n";
	echo $this->Form->control('id', ['label' => 'Plin', 'type' => 'text']);
	echo $this->Form->control('password', ['type'=>'password']);
	echo $this->Form->button(__('Login'));
	echo "</fieldset>\n";
	echo $this->Form->end();
}
