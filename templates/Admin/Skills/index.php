<?php

use App\Model\Enum\Authorization;

/**
 * @var \App\View\AdminView $this
 * @var ?\App\Model\Entity\Player $user
 * @var array $objs
 */

$isSuper = $user?->hasAuth(Authorization::Super);
?>
<h3>Skills</h3>
<?php if ($isSuper) : ?>
<p><?= $this->Html->link('Add Skill', ['action' => 'add']) ?></p>
<?php endif; ?>
<table>
    <tr>
        <th>Id</th>
        <th>Sort</th>
        <th>Name (Cost,Loresheet,Blanks)</th>
        <th>Max</th>
        <th>Mana</th>
        <th>Deprecated</th>
        <?php if ($isSuper) : ?>
        <th>Actions</th>
        <?php endif; ?>
    </tr>
    <?php foreach ($objs as $obj) : ?>
    <tr>
        <td><?= h($obj->get('id')) ?></td>
        <td><?= h($obj->get('sort_order')) ?></td>
        <td><?= h($obj->get('name')) ?> <?= implode(',', array_filter([
            '(' . h($obj->get('cost')),
            ($obj->get('loresheet') ? 'L' : null),
            ($obj->get('blanks') ? 'B' : null),
        ])) ?>)</td>
        <td><?= h($obj->get('times_max')) ?></td>
        <td><?= h($obj->get('mana_amount')) ?> <?= h($obj->get('manatype')?->get('name')) ?></td>
        <td><?= $obj->get('deprecated') ? 'True' : 'False' ?></td>
        <?php if ($isSuper) : ?>
        <td>
            <?= $this->Html->link('Edit', ['action' => 'edit', $obj->id]) ?>
            <?= $this->Form->postLink('Delete', ['action' => 'delete', $obj->get('id')]) ?>
        </td>
        <?php endif; ?>
    </tr>
    <?php endforeach; ?>
</table>
