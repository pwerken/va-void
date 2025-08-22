<?php
declare(strict_types=1);
/**
 * @var \App\View\AdminView $this
 * @var array $queries
 * @var string $selected
 * @var string $since
 * @var string $description
 * @var bool $aggregate
 * @var array $data
 */
?>
<h3>Statistics</h3>
<?php

$optionsStats = [];
$optionsStats['type'] = 'select';
$optionsStats['label'] = false;
$optionsStats['value'] = $selected;
$optionsStats['options'] = $queries;

$optionsSince = [];
$optionsSince['type'] = 'date';
$optionsSince['label'] = 'Modified since';
$optionsSince['value'] = $since;
$optionsSince['maxlength'] = 10;

echo $this->Form->create(null, ['type' => 'GET'])
    . $this->Form->control('selected', $optionsStats)
    . '<br/>' . PHP_EOL
    . $this->Form->control('since', $optionsSince)
    . $this->Form->button('Select')
    . $this->Form->end()
    . PHP_EOL;

if (count($data) == 0) {
    return;
}

echo '<p>' . $description . '</p>' . PHP_EOL;

if ($aggregate !== false) {
    $value = null;
    $times = 0;

    echo '<table>' . PHP_EOL
        . '<tr>'
        . '<th>Times</th>'
        . '<th>' . $aggregate . '</th>'
        . '</tr>'
        . PHP_EOL;

    foreach ($data as $row) {
        if (is_null($value)) {
            $value = $row['value'];
        }
        if ($value === $row['value']) {
            $times++;
            continue;
        }

        echo '<tr>'
            . '<td>' . $times . '</td>'
            . '<td>' . $value . '</td>'
            . '</tr>'
            . PHP_EOL;

        $value = null;
        $times = 0;
    }
    if (!is_null($value)) {
        echo '<tr>'
            . '<td>' . $times . '</td>'
            . '<td>' . $value . '</td>'
            . '</tr>'
            . PHP_EOL;
    }
    echo '</table>' . PHP_EOL;

    return;
}

echo '<table>' . PHP_EOL
    . '<tr>'
    . '<th>PLIN</th>'
    . '<th>Character</th>'
    . '<th>#</th>'
    . '</tr>'
    . PHP_EOL;

foreach ($data as $row) {
    $link = [ 'controller' => 'History', 'action' => 'character', $row['plin'] , $row['chin']];

    echo '<tr>'
        . '<td>' . $row['plin'] . '-' . $row['chin'] . '</td>'
        . '<td>' . $this->Html->link($row['name'], $link) . '</td>'
        . '<td>' . $row['value'] . '</td>'
        . '</tr>'
        . PHP_EOL;
}
echo '</table>' . PHP_EOL;
