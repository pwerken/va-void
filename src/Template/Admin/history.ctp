<h3>Entity History</h3>

<?php
$style = ['style' => 'display: inline-block; width: auto; margin-right: 1rem'];
echo $this->Form->create();
$style['default'] = $since;
echo 'Since: ' . $this->Form->text('since', $style);
$style['default'] = $plin;
echo 'Plin: ' . $this->Form->text('plin', $style);
echo $this->Form->button(__('Update'));
echo $this->Form->end();
?>

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
		."<td>".$this->Html->link($name, $link)." <em>".$row['name']."</em></td>\n"
		."<td>".$row['modified']." by ".$modifier."</td>\n"
		."</tr>\n";

}
if(empty($list)) {
	echo "<tr>\n<td><em>No matches</em></td>\n</tr>\n";
}

?>
</table>
