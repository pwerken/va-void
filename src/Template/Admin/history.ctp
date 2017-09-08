<h3>Entity History</h3>

<table>
<tr>
	<th>entity</th>
	<th>last modified by</th>
</tr>
<?php

foreach($list as $row) {
	$link = $row['entity'].'/'.$row['key1'];
	if(!is_null($row['key2']))
		$link .= '/'.$row['key2'];

	$modifier = $row['modifier_id'];
	if(is_null($modifier))
		$modifier = '(??)';
	if($modifier < 0)
		$modifier = '(cli)';

	echo "<tr>\n"
		."<td>".$this->Html->link($link, '/admin/history/'.$link)."</td>\n"
		."<td>".$row['modified']." by ".$modifier."</td>\n"
		."</tr>\n";

}

?>
</table>
