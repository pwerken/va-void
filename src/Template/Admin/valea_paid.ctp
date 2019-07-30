<h3>VA Leden Administratie ~ Pre-registration</h3>

<?php

$year = date("Y");
$events = [ 1 => 'Sumdag'
		  , 2 => 'Moots 1'
		  , 3 => 'Summoning'
		  , 4 => 'Moots 2'
		  ];

echo $this->Form->create();
echo $this->Form->text('year', ['default' => $year]);
echo $this->Form->select('event', $events);
echo $this->Form->button('Show list');
echo "&nbsp;<strong>Found ".count($players)."</strong>";
echo $this->Form->end();

if(count($players) == 0) {
	return;
}

?>
<table>
<tr>
	<th>PLIN</th>
	<th>voornaam</th>
	<th>tussenvoegsels</th>
	<th>achternaam</th>
	<th>crew</th>
	<th>monster</th>
	<th>EHBO</th>
	<th>bestuur</th>
	<th>eventlid</th>
	<th>toegangbetaald</th>
	<th>k12</th>
	<th>k16</th>
	<th>speciaal</th>
</tr>
<?php
	foreach($players as $player) {
		echo '<tr>';
		foreach($player as $col) {
			echo '<td>'.$col.'</td>';
		}
	}
?>
</table>
<?php
