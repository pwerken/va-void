<?php
declare(strict_types=1);
/**
 * @var \Cake\View\View $this
 * @var array $list
 * @var string $what
 * @var string $since
 * @var string $plin
 */
?>
<h3>Entity History</h3>
<?php

echo $this->Form->create(null, ['type' => 'get']);

$options = [];
$options['type'] = 'select';
$optionp['label'] = 'What';
$optoins['value'] = $what;
$options['options'] = [
    '' => 'All',
    'Players' => 'Players',
    'Characters' => 'Characters',
    'Items' => 'Items',
    'Conditions' => 'Conditions',
    'Powers' => 'Powers',
];
echo $this->Form->control('what', $options);

$options = [];
$options['type'] = 'date';
$options['label'] = 'Since';
$options['value'] = $since;
$options['maxlength'] = 10;
echo $this->Form->control('since', $options);

$options = [];
$options['type'] = 'text';
$options['label'] = 'By Plin';
$options['class'] = 'plin';
$options['value'] = $plin;
$options['maxlength'] = 4;
echo $this->Form->control('plin', $options);

echo $this->Form->button(__('Select'));
echo $this->Form->end() . "\n";

foreach ($list as $row) {
    $link = ['controller' => 'History', 'action' => strtolower($row['entity']), $row['key1']];
    $name = $row['entity'] . '/' . $row['key1'];
    if (!is_null($row['key2'])) {
        $name .= '/' . $row['key2'];
        $link[] = $row['key2'];
    }
    $name .= ': ' . $row['name'];

    $modifier = $modifier_id = $row['modifier_id'];
    if (is_null($modifier)) {
        $modifier = '(??)';
    }
    if ($modifier < 0) {
        $modifier = '_cli';
    }
    $tooltip = '';
    if (isset($modifier_names[$modifier_id])) {
        $tooltip = ' title="' . $modifier_names[$modifier_id] . '"';
    }

    echo '<samp' . $tooltip . '>'
        . str_pad($row['modified'], 19, '_', STR_PAD_BOTH) . ' '
        . str_pad((string)$modifier, 4, '0', STR_PAD_LEFT)
        . '</samp> '
        . $this->Html->link($name, $link) . "<br/>\n";
}
