<h3>Entity History</h3>

<?php
echo $this->Form->create(null, ['type' => 'get']);
$style = ['style' => 'display: inline-block; width: auto; margin-right: 1rem'];

$style['value'] = $what;
$values =
    [ ''            => 'All'
    , 'Players'     => 'Players'
    , 'Characters'  => 'Characters'
    , 'Items'       => 'Items'
    , 'Conditions'  => 'Conditions'
    , 'Powers'      => 'Powers'
    ];
echo $this->Form->select('what', $values, $style);

$style['value'] = $since;
echo 'Since: ' . $this->Form->text('since', $style);

$style['value'] = $plin;
echo 'Plin: ' . $this->Form->text('plin', $style);

echo $this->Form->button(__('Update'));
echo $this->Form->end() . "\n";

$highlight = '';
if($plin) {
    $highlight = "?highlight=$plin";
}

echo "<samp>\n";
foreach($list as $row) {
	$name = $row['entity'].'/'.$row['key1'];
	if(!is_null($row['key2']))
		$name .= '/'.$row['key2'];
	$link = '/admin/history/'.strtolower($name);
	$name .= ": ".$row['name'];

	$modifier = $row['modifier_id'];
	if(is_null($modifier))
		$modifier = '(??)';
	if($modifier < 0)
		$modifier = '_cli';

	echo str_pad($row['modified'], 19, '_', STR_PAD_BOTH) . " "
		. $this->Html->link(str_pad($modifier, 4, '0', STR_PAD_LEFT), $link.'?verbose')
		." " . $this->Html->link($name, $link.$highlight)."<br/>\n";
}
echo "</samp>\n";
