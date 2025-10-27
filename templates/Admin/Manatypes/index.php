<?php
declare(strict_types=1);

use App\Model\Enum\Authorization;

/**
 * @var \App\View\AdminView $this
 * @var ?\App\Model\Entity\Player $user
 * @var array $objs
 */

$isSuper = $user?->hasAuth(Authorization::Super);
?>
<h3>Manatypes</h3>
<?php if ($isSuper) : ?>
<p><?= $this->Html->link('Add Manatype', ['action' => 'add']) ?></p>
<?php endif; ?>
<table>
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Deprecated</th>
        <?php if ($isSuper) : ?>
        <th>Actions</th>
        <?php endif; ?>
    </tr>
    <?php foreach ($objs as $obj) : ?>
    <tr>
        <td><?= h($obj->get('id')) ?></td>
        <td><?= h($obj->get('name')) ?></td>
        <td><?= $obj->get('deprecated') ? 'True' : 'False' ?></td>
        <?php if ($isSuper) : ?>
        <td>
            <?= $this->Html->link('Edit', ['action' => 'edit', $obj->get('id')]) ?>
            <?= $this->Form->postLink('Delete', ['action' => 'delete', $obj->get('id')]) ?>
        </td>
        <?php endif; ?>
    </tr>
    <?php endforeach; ?>
</table>
