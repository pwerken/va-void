<?php
declare(strict_types=1);
/**
 * @var \Cake\View\View $this
 * @var \Cake\Datasource\ResultSetInterface $logins
 */

$count = $logins->count();
$link = ['controller' => 'Social', 'action' => 'index'];
?>
<h3>Social media authentication</h3>

<p>
There are <?= $this->Html->link((string)$count, $link) ?> social media logins stored.
</p>

<table>
<tr>
    <th>Plin</th>
    <th>Modified</th>
    <th>Email (source/reference)</th>
    <th></th>
    <th></th>
</tr>
<?php
foreach ($logins as $login) {
    $plin = $login->get('user_id');
    if ($plin) {
        $link = ['controller' => 'History', 'action' => 'player', $plin];
        $player = $this->Html->link((string)$plin, $link);
    } else {
        $player = '';
    }

    echo $this->Form->create(null, ['inputDefaults' => ['label' => false, 'div' => false]])
        . '<tr>'
        . '<td>'
        . $this->Form->hidden('social', ['value' => $login->get('id')])
        . $player
        . '</td><td>'
        . $login['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss')
        . '</td><td>'
        . ($login['email'] ?? '<i>null</i>')
        . '&nbsp;('
        . $login['provider'] . ' - ' . ($login['full_name'] ?? $login['username'] ?? '<i>null</i>')
        . ')'
        . '</td><td>'
        . (isset($plin) ? $this->Form->button('Unlink', ['name' => 'unlink']) : '')
        . '</td><td>'
        . $this->Form->button('Delete', ['name' => 'delete'])
        . '</td>'
        . $this->Form->end()
        . '</tr>'
        . "\n";
}
?>
</table>
