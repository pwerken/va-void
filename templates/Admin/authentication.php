<?php
use App\Model\Entity\Player;
use Cake\ORM\TableRegistry;

if($user->hasAuth('infobalie')):

$inputStyle = 'width:5em;display:inline-block;margin-right:1rem;';

$profiles = TableRegistry::get('SocialProfiles');

$total = $profiles->find()->count();
$users = $profiles->find()->distinct('user_id')->where(['user_id IS NOT NULL'])->count();

$noPlins = $profiles->find()
                ->where(['user_id IS NULL'])
                ->orderDesc('modified')
                ->all();


?>
<h3>Social media authentication</h3>

<p>
There are <?=$total?> social media logins stored, linked to
<?=$users?> different players.
</p>
<?php if($noPlins->count() > 0): ?>
<p>
With <b><?=$noPlins->count()?></b> social media logins where the provided email
(if any) did <b>not</b> match a known player's email.
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
