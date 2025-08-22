<?php

use App\Model\Enum\Authorization;

/**
 * @var \Cake\View\View $this
 * @var ?\App\Model\Entity\Player $user
 */
$site = 'VOID-API';

$hasAuthPlayer = $user?->hasAuth(Authorization::Player);
$hasAuthReadOnly = $user?->hasAuth(Authorization::ReadOnly);
#$hasAuthReferee = $user?->hasAuth(Authorization::Referee);
$hasAuthInfobalie = $user?->hasAuth(Authorization::Infobalie);

?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $site ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css(['normalize.min', 'milligram.min']) ?>
    <?= $this->Html->css(['fonts', 'cake', 'void', 'dark']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="navigation">
        <section class="container">
            <div class="navigation-item">
                <a class="navigation-title" href="#popover-site" data-popover>
                    <?= $site ?>
                </a>
                <div class="popover" id="popover-site">
                    <ul class="popover-list">
                        <li class="popover-item">
                            <a class="popover-link" href="/admin">Home</a>
                        </li>
                        <li class="popover-item">
                            <a class="popover-link" href="/admin/checks">Config Checks</a>
                        </li>
<?php if ($hasAuthPlayer) : ?>
                        <li class="popover-item">
                            <a class="popover-link" href="/admin/routes">Routes</a>
                        </li>
<?php endif ?>
                    </ul>
                </div>
            </div>

            <div class="navigation-item not-on-mobile">
                <a href="/admin">
                    <small>
                        <?= $user?->get('name') ?><br/>
                        <em><?= $user?->get('role')->label() ?></em>
                    </small>
                </a>
            </div>

<?php if ($hasAuthPlayer) : ?>
            <ul class="navigation-list float-right">
    <?php if ($hasAuthReadOnly) : ?>
                <li class="navigation-item">
                    <a class="navigation-link" href="/admin/history" title="History">
                        <img src="/img/icons/clock.svg">
                    </a>
                </li>
                <li class="navigation-item">
                    <a class="navigation-link" href="/admin/printing" title="Printing">
                        <img src="/img/icons/printer.svg">
                    </a>
                </li>
                <li class="navigation-item">
                    <a class="navigation-link" href="#popover-db" data-popover title="Database">
                        <img src="/img/icons/database.svg">
                    </a>
                    <div class="popover" id="popover-db">
                        <ul class="popover-list">
                            <li class="popover-item">
                                <a class="popover-link" href="/admin/stats">Statistics</a>
                            </li>
                            <li class="popover-item">
                                <a class="popover-link" href="/admin/skills">Skills lookup</a>
                            </li>
        <?php if ($hasAuthInfobalie) : ?>
                            <li class="popover-item">
                                <a class="popover-link" href="/admin/backups">Backups</a>
                            </li>
                            <li class="popover-item">
                                <a class="popover-link" href="/admin/migrations">Migrations</a>
                            </li>
        <?php endif ?>
                        </ul>
                    </div>
                </li>
    <?php endif ?>
                <li class="navigation-item">
                    <a class="navigation-link" href="#popover-users" data-popover title="Access control">
                        <img src="/img/icons/person-bounding-box.svg">
                    </a>
                    <div class="popover" id="popover-users">
                        <ul class="popover-list">
    <?php if ($hasAuthReadOnly) : ?>
                            <li class="popover-item">
                                <a class="popover-link" href="/admin/authorization">Authorization</a>
                            </li>
    <?php endif ?>
    <?php if ($hasAuthInfobalie) : ?>
                            <li class="popover-item">
                                <a class="popover-link" href="/admin/social">Authentication</a>
                            </li>
    <?php endif ?>
                            <li class="popover-item">
                                <a class="popover-link" href="/admin/password">Password</a>
                            </li>
                            <li class="popover-item">
                                <a class="popover-link" href="/admin/logout">Logout</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
<?php endif ?>
        </section>
    </nav>

    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        <div>
    </main>

    <footer>
    </footer>

    <?= $this->Html->script(['void']) ?>
</body>
</html>
