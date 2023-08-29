<?php
use App\Model\Entity\Player;
use Cake\ORM\TableRegistry;

$role = $user?->get('role');

switch($role) {
case 'Super':
case 'Infobalie':
    $access = true;
    break;
default:
    $access = false;
}

if($access) {

$profiles = TableRegistry::get('SocialProfiles');

$total = $profiles->find()->count();
$users = $profiles->find()->distinct('user_id')->where(['user_id IS NOT NULL'])->count();

$noPlins = $profiles->find()
                ->where(['user_id IS NULL'])
                ->orderDesc('modified')
                ->all();
?>
<h3>Social media authentication</h3>

There are <?=$total?> social media logins stored, linked to
<?=$users?> different players.
<p>
<p>
With <b><?=$noPlins->count()?></b> social media logins where the provided email
(if any) did <b>not</b> match a known player's email.

<ul>
<?php
foreach($noPlins as $noPlin):
?>
<li><?=$noPlin['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss')?>
  <?=$noPlin['email'] ?? '<i>null</i>'?>
 (<?=$noPlin['provider']?> -
  <?=$noPlin['full_name'] ?? $noPlin['username'] ?? '<i>null</i>'?>)
<?php
endforeach;
?>
</ul>

<hr>
<?php
}
?>

<h3>Legacy password authentication</h3>
<?php

echo $this->Form->create();
echo $this->Form->control('plin'
		, ['label' => 'Plin', 'type' => 'text']);
echo $this->Form->control('password'
		, ['label' => 'Password', 'type' => 'password']);
echo $this->Form->button(__('Set password'));

