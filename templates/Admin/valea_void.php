<h3>VA Leden Administratie ~ VOID player-data synchronisation</h3>

<?php
echo $this->Form->create();
echo $this->Form->button('Perform checked actions');
?>

<table class="cmp">
<tr>
	<th></th>
	<th>VOID Action</th>
	<th>PLIN</th>
	<th>voornaam</th>
	<th>tussenvoegsels</th>
	<th>achternaam</th>
	<th>geboortedatum</th>
	<th>M/F</th>
</tr>
<?php

foreach($diff as $row) {

	switch($row[0]) {
	case -1:	$cmp = 'onlyValea'; break;
	case 0:		$cmp = 'different'; break;
	case 1:		$cmp = 'onlyVoid';  break;
	}

	echo "<tr class=\"$cmp\">\n<td>";
	switch($row[0]) {
	case -1:	echo '<s>void</s><br/>valea'; break;
	case 0:		echo 'void</br>valea'; break;
	case 1:		echo 'void<br/><s>valea</s>'; break;
	}
	echo "</td>\n<td>\n";

	$options = ['hiddenField' => false];
	$options['value'] = '';
	if($row[0] < 0 && !is_null($row[1][0][2])) {
		$options['value'] = 'insert';
		echo $this->Form->checkbox('action['.$row[1][0][2].']', $options);
	} elseif($row[0] == 0) {
		$options['value'] = 'update';
		echo $this->Form->checkbox('action['.$row[1][0][1].']', $options);
	} elseif($row[0] > 0) {
		$options['value'] = 'delete';
		echo $this->Form->checkbox('action['.$row[1][0][1].']', $options);
	}
	echo $options['value'] . "</td>\n";
	foreach($row[1] as $cell) {
		$cls = $cell[0] ? '' : ' class="here"';
		echo "<td$cls>$cell[1]&nbsp;<br/>$cell[2]&nbsp;</td>\n";
	}

	echo "</tr>\n";
}

?>
</table>

<?php
echo $this->Form->end();
