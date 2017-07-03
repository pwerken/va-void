<?php

$cakeDescription = 'VOID';
$nav =	[ '/admin/authentication' => 'Authentication'
		, '/admin/authorisation' => 'Authorisation'
		, '/admin/checks' => 'Check Configuration'
		, '/admin/routes' => 'Configured Routes'
		];
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
</head>
<body>
    <nav class="top-bar expanded" data-topbar role="navigation">
        <ul class="title-area large-3 medium-4 columns">
            <li class="name">
                <h1><a href="/"><?= $cakeDescription ?></a></h1>
            </li>
        </ul>
        <div class="top-bar-section">
            <ul class="right">
<?php if(!isset($user)) : ?>
                <li><a target="_blank" href="/admin">LOGIN</a></li>
<?php else: ?>
				<li><a href="/players/<?=$user['id']?>"><?= $user['full_name'] ?> (<?= $user['role'] ?>)</a></li>
                <li><a target="_blank" href="/admin/logout">LOGOUT</a></li>
<?php endif ?>
            </ul>
        </div>
    </nav>
    <?= $this->Flash->render() ?>
    <div class="container clearfix">
		<nav class="large-3 medium-4 columns" id="actions-sidebar">
			<ul class="side-nav">
				<li class="heading"><a href="/admin"><?= __('Admin links') ?></a></li>

<?php
foreach($nav as $url => $descr) {
	echo '<li>'.$this->Html->link($descr, $url)."</il>\n";
}
?>
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
