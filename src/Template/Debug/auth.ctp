<?php
use App\Model\Entity\Player;
use Cake\ORM\TableRegistry;

?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Debug links') ?></li>
<?php
foreach($links as $url => $descr)
	echo '<li>'.$this->Html->link($descr, $url)."</il>\n";
?>
    </ul>
</nav>
<div class="players index large-9 medium-8 columns content">
    <h3><?= __('Account authorisations') ?></h3>
<?php

$players = TableRegistry::get('players');
foreach(array_reverse(Player::roleValues()) as $role)
{
	echo "\n<h4>$role</h4>\n";

	if(!isset($perms[$role])) {
		echo "<p>There are <em>NO</em> accounts with this role.</p>\n";
		continue;
	}
	if(count($perms[$role]) > 100) {
		echo "<p>A total of <em>".count($perms[$role])
			."</em> accounts with this role.</p>\n";
		continue;
	}

	$query = $players->find();
	$query->where(['role' => $role]);

	$url = [];
	$url['controller'] = 'Players';
	$url['action'] = 'view';
	$url['_method'] = 'GET';

	echo "<p>\n  <ul>\n";
	foreach($query as $player) {
		$url[0] = $player->id;
		$descr = $player->id . ': '.$player->full_name;
		echo "    <li>" . $this->Html->link($descr, $url)."\n";
	}
	echo "  </ul>\n";
}

?>
</div>
