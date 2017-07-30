<h3>VA Leden Administratie</h3>

<table class="cmp">
<tr>
	<th></th>
	<th>PLIN</th>
	<th>voornaam</th>
	<th>tussenvoegsels</th>
	<th>achternaam</th>
	<th>geboortedatum</th>
	<th>M/F</th>
	<th>Action</th>
</tr>
<?php

foreach($diff as $row) {

	switch($row[0]) {
	case -1:	$cmp = 'onlyVelea'; break;
	case 0:		$cmp = 'different'; break;
	case 1:		$cmp = 'onlyVoid'; break;
	}

	echo "<tr class=\"$cmp\">\n"
		."<td>$cmp</td>\n";

	foreach($row[1] as $cell) {
		$cls = $cell[0] ? '' : ' class="here"';
		echo "<td$cls>$cell[1]&nbsp;<br/>$cell[2]&nbsp;</td>\n";
	}

	echo "<td>";
	if($row[0] < 0 && !is_null($row[1][0][2])) {
		echo $this->Form->create();
		echo '<input type="hidden" name="insert" value="'.$row[1][0][2].'">';
		echo '<button class="green" type="submit">+</button>';
		echo $this->Form->end();
	} elseif($row[0] == 0) {
		echo $this->Form->create();
		echo '<input type="hidden" name="update" value="'.$row[1][0][1].'">';
		echo '<button class="blue" type="submit">+</button>';
		echo $this->Form->end();
	} elseif($row[0] > 0) {
		echo $this->Form->create();
		echo '<input type="hidden" name="delete" value="'.$row[1][0][1].'">';
		echo '<button class="red" type="submit">-</button>';
		echo $this->Form->end();
	}
	echo "</td>\n";
	echo "</tr>\n";
}

?>
</table>
