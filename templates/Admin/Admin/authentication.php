<?php
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

    <?php foreach ($noPlins as $noPlin) :
        $id = $noPlin['id'];
        ?>
        <?=$this->Form->create(null, ['inputDefaults' => ['label' => false, 'div' => false]]); ?>
        <?=$this->Form->input('social', ['type' => 'hidden', 'value' => $id]); ?>
        <?=$this->Form->input('plin', ['type' => 'text', 'value' => '', 'style' => $inputStyle]); ?>
        <?=$this->Form->button("Link#$id"); ?>
&nbsp;
        <?=$this->Form->button("Delete#$id", ['name' => 'delete']); ?>
&nbsp;
        <?=$noPlin['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss')?>
&nbsp;
        <?=$noPlin['email'] ?? '<i>null</i>'?>
&nbsp;
(<?=$noPlin['provider']?> -
        <?=$noPlin['full_name'] ?? $noPlin['username'] ?? '<i>null</i>'?>)
&nbsp;
        <?=$this->Form->end(); ?>
    <?php endforeach;
    ?>
<?php else : ?>
<p>
All social media logins have an associated plin.
</p>
<?php endif; ?>
