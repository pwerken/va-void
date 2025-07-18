<?php
declare(strict_types=1);
/**
 * @var \Cake\View\View $this
 */
use Cake\ORM\TableRegistry;

$inputStyle = 'width:5em;display:inline-block;margin-right:1rem;';

$profiles = TableRegistry::getTableLocator()->get('SocialProfiles');

$total = $profiles->find()->count();

$noPlins = $profiles->find()
                ->where(['user_id IS NULL'])
                ->orderDesc('modified')
                ->all();

?>
<h3>Social media authentication</h3>

<p>
There are <?=$total?> social media logins stored.
</p>
<?php if ($noPlins->count() > 0) : ?>
<p>
With <b><?=$noPlins->count()?></b> social media login(s) where the provided
email did <b>not</b> match a known player's email.<br/>
This can be resolved by either updating the players information with the used
email adres (via voidwalker).</br>
Or by linking the specific login below to a specific plin.
</p>

    <?php
    echo '<table>';
    foreach ($noPlins as $noPlin) {
        $id = $noPlin['id'];

        echo $this->Form->create(null, ['inputDefaults' => ['label' => false, 'div' => false]])
            . '<tr>'
            . '<td>'
            . $this->Form->hidden('social', ['value' => $id])
            . $this->Form->widget('text', ['name' => 'plin', 'value' => '', 'style' => $inputStyle])
            . '</td><td>'
            . $this->Form->button("Link#$id")
            . '</td><td>'
            . $this->Form->button("Delete#$id", ['name' => 'delete'])
            . '</td><td>'
            . $noPlin['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss')
            . '</td><td>'
            . ($noPlin['email'] ?? '<i>null</i>')
            . '&nbsp;('
            . $noPlin['provider'] . ' - ' . ($noPlin['full_name'] ?? $noPlin['username'] ?? '<i>null</i>')
            . ')'
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
