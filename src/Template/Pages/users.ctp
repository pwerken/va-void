<?php
use App\Model\Entity\Player;
use Cake\ORM\TableRegistry;

?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
</nav>
<div class="players index large-9 medium-8 columns content">
    <h3><?= __('Account authorisations') ?></h3>

<?php

#	 'SELECT `role`, COUNT(*) FROM `players` GROUP BY `role`'
	$players = TableRegistry::get('players');
	$query = $players->find();
	$query->select(['role'], true);
	$query->select(['count' => $query->func()->count("*")]);
	$query->group('role');
	$query->hydrate(false);

	$permCounts = [];
	foreach($query->toArray() as $row) {
		$permCounts[$row['role']] = $row['count'];
	}
	foreach(Player::roleValues() as $role):
?>
	<h4><?= $role ?></h4>
<?php
		if(!isset($permCounts[$role])) :
?>
<p>There are <em>no</em> accounts with this role.</p>
<?php
			continue;
		endif;
		if(isset($permCounts[$role]) && $permCounts[$role] > 100) :
?>
<p>A total of <em><?= $permCounts[$role] ?></em> accounts with this role.</p>
<?php
			continue;
		endif;

		$players = TableRegistry::get('players');
		$query = $players->find();
		$query->where(['role' => $role]);

		$url = [];
		$url['controller'] = 'Players';
		$url['action'] = 'view';
		$url['_method'] = 'GET';

		echo '<p><ul>';
		foreach($query as $player) {
			$url[0] = $player->id;
			$descr = $player->id . ': '.$player->full_name;
			echo '<li>' . $this->Html->link($descr, $url);
		}
		echo '</ul></p>';

	endforeach;
?>
</div>
