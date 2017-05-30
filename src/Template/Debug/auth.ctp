<?php
use App\Model\Entity\Player;
use Cake\ORM\TableRegistry;

?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Debug links') ?></li>
        <li><?= $this->Html->link(__('View Configured Routes'), '/debug/routes') ?></li>
        <li><?= $this->Html->link(__('View Authorisations'), '/debug/auth') ?></li>
        <li><?= $this->Html->link(__('Create DB Password Hash'), '/debug/hash') ?></li>
        <li><?= $this->Html->link(__('Account Login / Logout'), '/debug/login') ?></li>
        <li><?= $this->Html->link(__('Set Player Password'), '/debug/password') ?></li>
        <li><?= $this->Html->link(__('Set Authorisation'), '/debug/role') ?></li>
    </ul>
</nav>
<div class="players index large-9 medium-8 columns content">
    <h3><?= __('Account authorisations') ?></h3>

<?php
if(is_null($user)) {
	echo "<h3>You need to be logged in!</h3>";
	echo "<p>".$this->Html->link('Click here to login.', '/debug/login')."</p>";
	echo "</div>";
	return;
}
?>
    <h3><?= __('Account authorisations') ?></h3>
<?php
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
