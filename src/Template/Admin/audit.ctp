<h3>Entity History</h3>

<table>
<tr>
	<th>entity</th>
	<th>last modified by</th>
</tr>
<?php

foreach($list as $row) {
	$link = $row[0].'/'.$row[1];
	if(!is_null($row[2]))
		$link .= '/'.$row[2];
	if(is_null($row[4]))
		$row[4] = '(??)';
	else if($row[4] < 0)
		$row[4] = '(cli)';

	echo "<tr>\n"
		."<td>".$link."</td>\n"
		."<td>".$row[3]." by ".$row[4]."</td>\n"
		."</tr>\n";
}

?>
</table>
