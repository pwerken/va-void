<?php
declare(strict_types=1);
/**
 * @var \Cake\View\View $this
 * @var array $history
 */
echo '<h3>Entity History</h3>' . PHP_EOL;

foreach ($history as $row) {
    $tooltip = '';
    $modifier_id = $row['modifier_id'];
    if (isset($modifier_names[$modifier_id])) {
        $tooltip = ' title="' . $modifier_names[$modifier_id] . '"';
    }

    echo '<samp' . $tooltip . '>'
        . str_pad($row['modified'], 19, '_', STR_PAD_BOTH) . ' ' . $row['modifier']
        . '</samp> ';

    $close = '';
    if ($row['link']) {
        echo '<a href="/admin/history' . $row['link'] . '">';
        $close = '</a>';
    }
    echo $row['key'] . $close;
    echo '<br class="on-mobile"/>';

    echo ' <span class="' . $row['state'] . '"><strong>';
    if ($row['name'] === null) {
        echo '<em>(removed)</em>';
    } else {
        echo $row['name'];
    }
    echo '</strong></span> ';

    if ($row['link']) {
        echo '</a>';
    }

    if (isset($row['field'])) {
        $value = var_export($row['value'], true);

        echo '<span class="' . $row['state'] . '">'
            . '<em>' . $row['field'] . '</em>: ' . nl2br($value)
            . '</span>';
    }
    echo "<br/>\n";
}
