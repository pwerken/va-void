<?php
declare(strict_types=1);
/**
 * @var \Cake\View\View $this
 * @var ?\App\Model\Entity\Player $user
 * @var list<string> $roles
 * @var array $permissions
 */
?>
<h3>Authorization</h3>
<?php

$descriptions = [
    'Player' => 'Can view own player data and related characters, items, conditions and powers.' . PHP_EOL .
                ' Can edit own player data and login credentials.' . PHP_EOL .
                ' Can download pdf for character with related conditions and powers.',
    'Read-only' => 'Same as "Player", plus: can view all players, characters, items, powers and conditions,' .
                ' including notes and referee notes fields.' . PHP_EOL .
                ' Can view the print queue.',
    'Referee' => 'Same as "Read-only", plus: can add and edit characters, items, powers, and conditions.' . PHP_EOL .
                ' Can add lammies to the print queue.',
    'Infobalie' => 'Same as "Referee", plus: can add and edit players.' . PHP_EOL .
                ' Can download pdf from the print queue.',
    'Event Control' => 'Same as "Infobalie".',
];

$options = [];
foreach ($roles as $role) {
    $options[$role] = $role;
}

if ($user?->hasAuth('referee')) {
    echo $this->Form->create(null, ['url' => ['controller' => 'Authorization', 'action' => 'edit']])
        . $this->Form->control('plin', [
            'label' => 'Plin',
            'class' => 'plin',
            'type' => 'number',
            'min' => 0,
            'max' => 9999,
            'maxlength' => 4,
            ])
        . $this->Form->control('role', ['label' => 'Role', 'options' => $options])
        . $this->Form->button('Set')
        . $this->Form->end();
}

echo "<table>\n";
foreach (array_reverse($roles) as $role) {
    echo '<tr><th colspan="2">' . $role .
        '<br/><small>Permissions: ' . $descriptions[$role] . '</small>' .
        "</th></tr>\n";

    if (!isset($permissions[$role])) {
        echo '<tr><td/><td>'
            . 'There are <em>NO</em> accounts with this role.'
            . "</td></tr>\n";
        continue;
    }
    if (count($permissions[$role]) > 100) {
        echo '<tr><td/><td>'
            . 'A total of <em>' . count($permissions[$role]) . '</em> accounts with this role.'
            . "</td></tr>\n";
        continue;
    }

    foreach ($permissions[$role] as $plin => $name) {
        $url = ['controller' => 'History', 'action' => 'player', $plin];
        echo '<tr><td>'
            . $this->Html->link((string)$plin, $url)
            . '</td><td>'
            . $this->Html->link($name, $url)
            . "</td></tr>\n";
    }
}
echo "</table>\n";
