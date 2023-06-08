<?php

use Cake\Routing\Router;

$cakeDescription = 'VOID-API';

$role = (!isset($user) ? '' : $user['role']);

$nav = [];
switch($role) {
case 'Super':
case 'Infobalie':
	$nav['/admin/migrations'] = 'Database Migrations';
	$nav['/admin/backups'] = 'Database Backups';
case 'Referee':
case 'Read-only':
	$nav['/admin/authorization'] = 'Authorization';
	$nav['/admin/history'] = 'Entity History';
	$nav['/admin/printing'] = 'Printing Queue';
	$nav['/admin/skills'] = 'Skill~Player Lookup';
case 'Player':
	$nav['/admin/authentication'] = 'Authentication';
default:
	$nav['/admin/checks'] = 'Check Configuration';
	$nav['/admin/routes'] = 'Configured Routes';
	asort($nav);
}

?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('style.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="top-bar expanded" data-topbar role="navigation">
        <ul class="title-area large-3 medium-4 columns">
            <li class="name">
				<h1><?= $this->Html->link($cakeDescription, '/') ?></h1>
            </li>
        </ul>
        <div class="top-bar-section">
            <ul class="right">
<?php if(!isset($user)) : ?>
				<li><?= $this->Html->link('LOGIN', '/admin') ?></li>
<?php else:
				$descr = $user['full_name'].' ('.$user['role'].')';
				$link = '/players/'.$user['id'];
?>
				<li><?= $this->Html->link($descr, $link); ?></li>
				<li><?= $this->Html->link('LOGOUT', '/admin/logout') ?></li>
<?php endif ?>
            </ul>
        </div>
    </nav>
    <?= $this->Flash->render() ?>
    <div class="container clearfix">
		<nav class="large-3 medium-4 columns" id="actions-sidebar">
			<ul class="side-nav">
				<li class="heading"><?= $this->Html->link('Administrator ', '/admin') ?></li>
<?php foreach($nav as $url => $descr) : ?>
				<li><?= $this->Html->link($descr, $url) ?></il>
<?php endforeach; ?>
			</ul>
		</nav>
		<div class="large-9 medium-8 columns content">
<?= $this->fetch('content') ?>
		</div>
	</div>
    <footer>
    </footer>
</body>
</html>
