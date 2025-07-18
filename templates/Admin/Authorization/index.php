<?php
declare(strict_types=1);
/**
 * @var \Cake\View\View $this
 * @var list<string> $roles
 */
use Cake\ORM\TableRegistry;

$options = [];
foreach ($roles as $role) {
    $options[$role] = $role;
}

$style = ['style' => 'display: inline-block; width: auto; margin-right: 1rem'];

echo '<h3>Authorization</h3>' . PHP_EOL;
echo $this->Form->create(null, [
        'url' => ['controller' => 'authorization', 'action' => 'edit'],
    ]);
echo 'Plin: ';
echo $this->Form->text('plin', $style) . PHP_EOL;
echo $this->Form->select('role', $options, $style) . PHP_EOL;
echo $this->Form->button(__('Set Role')) . PHP_EOL;
echo $this->Form->end();

$players = TableRegistry::getTableLocator()->get('Players');
$perms = $query = $players->find(
    'list',
    ['valueField' => 'id', 'groupField' => 'role'],
)->toArray();

echo "<table>\n";
foreach (array_reverse($roles) as $role) {
    echo '<tr><th colspan="2">' . $role . "</th></tr>\n";

    if (!isset($perms[$role])) {
        echo '<tr><td/><td>'
            . 'There are <em>NO</em> accounts with this role.'
            . "</td></tr>\n";
        continue;
    }
    if (count($perms[$role]) > 100) {
        echo '<tr><td/><td>'
            . 'A total of <em>' . count($perms[$role]) . '</em> accounts with this role.'
            . "</td></tr>\n";
        continue;
    }

    $query = $players->find();
    $query->where(['role' => $role]);

    foreach ($query as $player) {
        $url = ['controller' => 'history', 'action' => 'player', $player->id];
        echo '<tr><td>'
            . $this->Html->link((string)$player->id, $url)
            . '</td><td>'
            . $this->Html->link($player->full_name, $url)
            . "</td></tr>\n";
    }
}
echo "</table>\n";
