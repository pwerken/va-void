<h3>Entity History</h3>

<table>
<tr>
	<th>entity</th>
	<th>last modified by</th>
</tr>
<?php

foreach($list as $row) {
	$link = $row->get('entity').'/'.$row->get('key1');
	if(!is_null($row->get('key2')))
		$link .= '/'.$row->get('key2');

	echo "<tr>\n"
		."<td>".$this->Html->link($link, '/admin/history/'.$link)."</td>\n"
		."<td>".$row->modifiedString()." by ".$row->modifierString()."</td>\n"
		."</tr>\n";
}

?>
</table>
