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
</tr>
<?php

foreach($diff as $row) {
	echo "<tr class=\"$row[0]\">\n"
		."<td>$row[0]</td>\n";

	foreach($row[1] as $cell) {
		$cls = $cell[0] ? '' : ' class="here"';
		echo "<td$cls>$cell[1]&nbsp;<br/>$cell[2]&nbsp;</td>\n";
	}
	echo "</tr>\n";
}

?>
</table>
