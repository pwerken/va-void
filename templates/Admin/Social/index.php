<?php
declare(strict_types=1);
/**
 * @var \App\View\AdminView $this
 * @var \Cake\Datasource\ResultSetInterface $logins
 * @var int $total
 */
$link = ['controller' => 'Social', 'action' => 'all'];
?>
<h3>Authentication</h3>

<p>
We have <?= $this->Html->link((string)$total, $link) ?> stored logins.
</p>
<?php if ($logins->count() > 0) : ?>
<p>
For <b><?= $logins->count() ?></b> login(s) the provided email did <em>not</em>
match a known player's email.<br/>
This can be resolved by updating the players information with the used email
adres (via voidwalker).</br>
Or by linking the login below.
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
            . $this->Form->widget('plin', [
                'name' => 'plin',
                'class' => 'plin',
                'type' => 'number',
                'value' => '',
                'min' => 0,
                'max' => 9999,
                'maxlength' => 4,
                ])
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
