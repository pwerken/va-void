<h3>Entity History</h3>

<table>
<tr>
	<th>entity</th>
	<th>last modified by</th>
</tr>
<?php

foreach($list as $row) {
	$name = $row['entity'].'/'.$row['key1'];
	if(!is_null($row['key2']))
		$name .= '/'.$row['key2'];
	$link = '/admin/history/'.strtolower($name);

	$modifier = $row['modifier_id'];
	if(is_null($modifier))
		$modifier = '(??)';
	if($modifier < 0)
		$modifier = '(cli)';

	echo "<tr>\n"
		."<td>".$this->Html->link($name, $link)."</td>\n"
		."<td>".$row['modified']." by ".$modifier."</td>\n"
		."</tr>\n";

}

?>
</table>
