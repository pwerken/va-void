<?php
declare(strict_types=1);
/**
 * @var \App\View\AdminView $this
 * @var array $history
 * @var array $lookup
 * @var bool $rhs
 */
?>
<h3>Entity History</h3>
<?php

foreach ($history as $row) {
    $tooltip = '';
    $modifier_id = $row['modifier_id'];
    if (isset($lookup[$modifier_id])) {
        $tooltip = ' title="' . $lookup[$modifier_id]->get('name') . '"';
    }

    $name = $this->Helper->getName($row, $rhs);
    $old = $this->Helper->getName($row->get('prev'), $rhs);

    $prefix = '<samp' . $tooltip . '>'
        . str_pad($row->modifiedString(), 19, '_', STR_PAD_BOTH) . ' '
        . str_pad($row->modifierString(), 4, '0', STR_PAD_LEFT)
        . '</samp> ';

    $link = $this->Helper->relationLink($row, $rhs);
    if ($link) {
        $prefix .= $this->Html->link($row->makeKey(), $link) . ' ';
    } else {
        $prefix .= $row->makeKey() . ' ';
    }
    $suffix = '</span><br/>' . PHP_EOL;

    $data = $row->decode();
    $prev = $row->get('prev')?->decode() ?? [];

    unset($data['created']);
    unset($prev['created']);
    unset($data['creator_id']);
    unset($prev['creator_id']);
    unset($data['modifier_id']);
    unset($data['modified']);
    unset($prev['modified']);
    unset($data['modifier_id']);
    unset($prev['modifier_id']);
    unset($data['skill_id']);
    unset($prev['skill_id']);

    if (is_null($row->get('data'))) {
        // removed entity
        $prefix .= '<span class="removed"><strong>' . h($old) . '</strong> ';
        if (empty($prev)) {
            echo $prefix . '<em>(removed)</em>' . $suffix;
            continue;
        }
        foreach ($prev as $field => $value) {
            echo $prefix . $this->Helper->formatField($field, $value) . $suffix;
        }
        continue;
    }

    if (empty($data)) {
        echo $prefix
            . '<span class="added"><strong>' . h($name) . '</strong>'
            . $suffix;
        continue;
    }
    foreach ($data as $field => $value) {
        if (array_key_exists($field, $prev) && $value == $prev[$field]) {
            // no change
            continue;
        }
        $class = (array_key_exists($field, $prev) ? 'modified' : 'added');
        echo $prefix
            . '<span class="' . $class . '"><strong>' . h($name) . '</strong> '
            . $this->Helper->formatField($field, $value)
            . $suffix;
    }

    foreach ($prev as $field => $value) {
        if (array_key_exists($field, $data)) {
            continue;
        }
        // removed fields (db changed)
        echo $prefix
            . '<span class="removed"><strong>' . h($name) . '</strong> '
            . $this->Helper->formatField($field, $value)
            . $suffix;
    }
}
