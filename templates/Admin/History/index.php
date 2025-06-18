<h3>Entity History</h3>

<?php
echo $this->Form->create(null, ['type' => 'get']);
$style = ['style' => 'display: inline-block; width: 15rem; margin-right: 1rem'];

$style['value'] = $what;
$values = [
    '' => 'All',
    'Players' => 'Players',
    'Characters' => 'Characters',
    'Items' => 'Items',
    'Conditions' => 'Conditions',
    'Powers' => 'Powers',
];
echo $this->Form->select('what', $values, $style);

$style['value'] = $since;
$style['maxlength'] = 10;
echo $this->Form->text('since', $style);

$style['value'] = $plin;
$style['maxlength'] = 4;
echo $this->Form->text('plin', $style);

echo $this->Form->button(__('Update'));
echo $this->Form->end() . "\n";

$highlight = '';
if ($plin) {
    $highlight = "?highlight=$plin";
}

echo "<samp>\n";
foreach ($list as $row) {
    $name = $row['entity'] . '/' . $row['key1'];
    if (!is_null($row['key2'])) {
        $name .= '/' . $row['key2'];
    }
    $link = '/admin/history/' . strtolower($name);
    $name .= ': ' . $row['name'];

    $modifier = $row['modifier_id'];
    if (is_null($modifier)) {
        $modifier = '(??)';
    }
    if ($modifier < 0) {
        $modifier = '_cli';
    }

    echo str_pad($row['modified'], 19, '_', STR_PAD_BOTH) . ' '
        . str_pad($modifier, 4, '0', STR_PAD_LEFT)
        . ' ' . $this->Html->link($name, $link . $highlight) . "<br/>\n";
}
echo "</samp>\n";
