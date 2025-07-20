<?php
declare(strict_types=1);
/**
 * @var \Cake\View\View $this
 * @var \Cake\Datasource\ResultSetInterface $logins
 * @var int $total
 */
$link = ['controller' => 'Social', 'action' => 'all'];
?>
<h3>Social media authentication</h3>

<p>
There are <?= $this->Html->link((string)$total, $link) ?> social media logins stored.
</p>
<?php if ($logins->count() > 0) : ?>
<p>
With <b><?= $logins->count() ?></b> social media login(s) where the provided
email did <b>not</b> match a known player's email.<br/>
This can be resolved by either updating the players information with the used
email adres (via voidwalker).</br>
Or by linking the login below to a plin.
</p>

<table>
<tr>
    <th>Plin</th>
    <th></th>
    <th>Modified</th>
    <th>Email (source/reference)</th>
    <th></th>
</tr>
    <?php
    foreach ($logins as $login) {
        $id = $login['id'];

        echo $this->Form->create(null, ['inputDefaults' => ['label' => false, 'div' => false]])
            . '<tr>'
            . '<td>'
            . $this->Form->hidden('social', ['value' => $id])
            . $this->Form->widget('text', ['name' => 'plin', 'value' => '', 'class' => 'plin'])
            . '</td><td>'
            . $this->Form->button('Link')
            . '</td><td>'
            . $login['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss')
            . '</td><td class="stretch">'
            . ($login['email'] ?? '<i>null</i>')
            . '&nbsp;('
            . $login['provider'] . ' - ' . ($login['full_name'] ?? $login['username'] ?? '<i>null</i>')
            . ')'
            . '</td><td>'
            . $this->Form->button('Delete', ['name' => 'delete'])
            . '</td>'
            . $this->Form->end()
            . '</tr>'
            . "\n";
    }
    echo '</table>';
else : ?>
<p>
All social media logins have an associated plin.
</p>
<?php endif; ?>
