<?php
use App\Model\Entity\Player;
use Cake\ORM\TableRegistry;

$profiles = TableRegistry::get('ADmad/SocialAuth.SocialProfiles');


$total = $profiles->find()->count();
$users = $profiles->find()->distinct('user_id')->where(['user_id IS NOT NULL'])->count();

$noPlins = $profiles->find()
                ->where(['user_id IS NULL'])
                ->orderDesc('modified')
                ->all();
?>
<h3>Social media authentication</h3>

We have <?=$total?> social media logins stored, linked to <?=$users?> different players.
<p>
<p>
There are <b><?=$noPlins->count()?></b> social media logins where the provided
email (if any) did <b>not</b> match a known player's email.

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
