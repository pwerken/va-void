<?php
use App\Model\Entity\Player;
use Cake\ORM\TableRegistry;

if($user->hasAuth('infobalie')):

$inputStyle = 'width:5em;display:inline-block;margin-right:1rem;';

$profiles = TableRegistry::get('SocialProfiles');

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
<?php if($noPlins->count() > 0): ?>
<p>
With <b><?=$noPlins->count()?></b> social media login(s) where the provided
email did <b>not</b> match a known player's email.<br/>
This can be resolved by either updating the players information with the used
email adres (via voidwalker).</br>
Or by linking the specific login below to a specific plin.
</p>

<?php foreach($noPlins as $noPlin): ?>
<?=$this->Form->create(null, ['inputDefaults' => ['label' => false, 'div' => false]]); ?>
<?=$this->Form->input('social', ['type' => 'hidden', 'value' => $noPlin['id']]); ?>
<?=$this->Form->input('plin', ['type' => 'text', 'value' => '', 'style' => $inputStyle]); ?>
<?=$this->Form->button(__('Link')); ?>
  <?=$noPlin['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss')?>
  <?=$noPlin['email'] ?? '<i>null</i>'?>
 (<?=$noPlin['provider']?> -
  <?=$noPlin['full_name'] ?? $noPlin['username'] ?? '<i>null</i>'?>)
<?=$this->Form->end(); ?>
<?php endforeach;
?>
<?php else: ?>
<p>
All social media logins have an associated plin.
</p>
<?php endif; ?>

<hr>
<?php endif; ?>

<h3>Legacy password authentication</h3>
<p>
If the password field is left blank, the password is removed.<br/>
</p>
<?php

echo $this->Form->create();
echo $this->Form->control('plin'
		, ['label' => 'Plin', 'type' => 'text', 'value' => '']);
echo $this->Form->control('password'
		, ['label' => 'Password', 'type' => 'password']);
echo $this->Form->button(__('Set password'));
echo $this->Form->end();
