<?php
use Cake\ORM\TableRegistry;

$options = [];
foreach ($roles as $role) {
    $options[] = [ 'value' => $role, 'text' => $role];
}

$style = ['style' => 'display: inline-block; width: auto; margin-right: 1rem'];

echo '<h3>Authorisation</h3>';
echo $this->Form->create();
echo 'Plin: ';
echo $this->Form->text('plin', $style);
echo $this->Form->select('role', $options, $style);
echo $this->Form->button(__('Set Role'));

$players = TableRegistry::getTableLocator()->get('Players');
$perms = $query = $players->find(
    'list',
    [ 'valueField' => 'id'
            , 'groupField' => 'role',
            ],
)->toArray();

foreach (array_reverse($roles) as $role) {
    echo "\n<h4>$role</h4>\n";

    if (!isset($perms[$role])) {
        echo "<p>There are <em>NO</em> accounts with this role.</p>\n";
        continue;
    }
    if (count($perms[$role]) > 100) {
        echo '<p>A total of <em>' . count($perms[$role])
            . "</em> accounts with this role.</p>\n";
        continue;
    }

    $query = $players->find();
    $query->where(['role' => $role]);

    echo "<p>\n  <ul>\n";
    foreach ($query as $player) {
        $url = '/admin/history/player/' . $player->id;
        $descr = $player->id . ': ' . $player->full_name;
        echo '    <li>' . $this->Html->link($descr, $url) . "\n";
    }
    echo "  </ul>\n";
}
