<?php

$cakeDescription = 'VOID-API';

$role = (!isset($user) ? '' : $user['role']);
switch($role) {
case 'Super':
	$nav['/admin/migrations'] = 'Database Migrations';
	$nav['/admin/valea'] = 'VALEA';
case 'Infobalie':
	$nav['/admin/backups'] = 'Database Backups';
	$nav['/admin/history'] = 'Entity History';
case 'Referee':
	$nav['/admin/authorisation'] = 'Authorisation';
	$nav['/admin/printing'] = 'Printing Queue';
case 'Player':
	$nav['/admin/authentication'] = 'Authentication';
default:
	$nav['/admin/checks'] = 'Check Configuration';
	$nav['/admin/routes'] = 'Configured Routes';
	asort($nav);
}

if(isset($nomenu) && $nomenu) {
	$nav = [];
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
    <?= $this->Html->css('cake.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

	<style>
.cmp td { vertical-align: middle; }
.cmp .different td.here { background-color: #ddf; }
.cmp .onlyValea, .cmp .onlyValea:nth-of-type(even)
{ background-color: #dfd; }
.cmp .onlyVoid, .cmp .onlyVoid:nth-of-type(even)
 { background-color: #fdd; }
.cmp button { margin: 0px; padding: 0.3em 1.5em; width: 4em; }
.cmp button.red { background-color: #FF0000; }
.cmp button.green { background-color: #009900; }
.cmp button.blue{ background-color: #007095; }
	</style>
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
