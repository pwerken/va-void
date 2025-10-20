<?php
declare(strict_types=1);

/**
 * @var \App\View\AdminView $this
 * @var array $list
 * @var array $lookup
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
$options['label'] = 'What';
$options['value'] = $what;
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
$options['type'] = 'number';
$options['label'] = 'By Plin';
$options['class'] = 'plin';
$options['value'] = $plin;
$options['min'] = 0;
$options['max'] = 9999;
$options['maxlength'] = 4;
echo $this->Form->control('plin', $options);
echo $this->Form->button(__('Select'));
echo $this->Form->end() . "\n";

foreach ($list as $row) {
    $link = $this->Helper->makeLink($row);
    $name = $row->makeKey() . ': ' . $this->Helper->getName($row);

    $tooltip = '';
    $modifier_id = $row['modifier_id'];
    if (isset($lookup[$modifier_id])) {
        $tooltip = ' title="' . h($lookup[$modifier_id]->get('name')) . '"';
    }

    echo '<samp' . $tooltip . '>'
        . str_pad($row->modifiedString(), 19, '_', STR_PAD_BOTH) . ' '
        . str_pad($row->modifierString(), 4, '0', STR_PAD_LEFT)
        . '</samp> '
        . $this->Html->link($name, $link) . "<br/>\n";
}
