<?php
declare(strict_types=1);

use App\Model\Enum\Authorization;
use App\Model\Enum\LammyStatus;

/**
 * @var \Cake\View\View $this
 * @var ?\App\Model\Entity\Player $user
 * @var array $printing
 */
?>
<h3>Printing Queue</h3>
<?php

$isInfobalie = $user?->hasAuth(Authorization::Infobalie);

$uniq = [];
$duplicates = 0;
$queued = 0;
foreach ($printing as $row) {
    if ($row['status'] != LammyStatus::Queued) {
        continue;
    }
    $queued++;
    $key = $row['entity'] . '_' . $row['key1'] . '_' . $row['key2'];
    if (
        isset($uniq[$key]) &&
        $row['entity'] != 'Power' &&
        $row['entity'] != 'Condition'
    ) {
        $duplicates++;
    }
    $uniq[$key] = $row['id'];
}

if ($isInfobalie) {
    echo "<strong>LET OP:</strong> klikken = gehele queue naar <em>'Printed'</em>. "
        . $this->Html->link(
            'Single-sided pdf',
            ['controller' => 'Printing', 'action' => 'single'],
            ['class' => 'button'],
        )
        . "<br/>\n"
        . "<strong>LET OP:</strong> klikken = gehele queue naar <em>'Printed'</em>. "
        . $this->Html->link(
            'Double-sided pdf',
            ['controller' => 'Printing', 'action' => 'double'],
            ['class' => 'button'],
        )
        . "<br/>\n";

    echo $this->Form->create()
        . $this->Form->button('Delete all selected');
}

echo " Queue size : $queued (";
if ($duplicates === 1) {
    echo '1 duplicate';
} else {
    echo $duplicates . ' duplicates';
}
echo ', <span id="checkboxcount"></span> selected)';

?>
<table>
<tr>
    <th></th>
    <th class="not-on-mobile">Status</th>
    <th class="not-on-mobile">By</th>
    <th>What</th>
    <th>Modified</th>
</tr>
<?php

foreach ($printing as $row) {
    $checkbox = '';
    $duplicate = false;
    if ($row['status'] == 'Queued') {
        $options = [];
        $options['type'] = 'checkbox';
        $options['value'] = $row['id'];
        $options['hiddenField'] = false;

        $key = $row['entity'] . '_' . $row['key1'] . '_' . $row['key2'];
        if (
            $uniq[$key] != $row['id'] &&
            $row['entity'] != 'Power' &&
            $row['entity'] != 'Condition'
        ) {
            $options['checked'] = true;
            $duplicate = true;
        }
        $checkbox = $this->Form->checkbox('delete[]', $options);
    }
    if (!is_null($row['created'])) {
        $row['created'] = $row['created']->i18nFormat('yyyy-MM-dd HH:mm:ss');
    }
    if (is_null($row['creator_id'])) {
        $row['creator_id'] = '(??)';
    }
    if (!is_null($row['modified'])) {
        $row['modified'] = $row['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss');
    }

    $key1 = $row['character_str'] ?: $row['key1'];
    $key2 = $row['key2'];

    if (substr($row['entity'], 0, 9) === 'Character' || $row['entity'] === 'Teaching') {
        $link = ['controller' => 'History', 'action' => 'character'] + explode('/', $key1, 2);
        $key1 = $this->Html->link((string)$key1, $link);
        if (substr($row['entity'], -5, 5) === 'Power') {
            $key2 = $this->Html->link((string)$key2, ['controller' => 'History', 'action' => 'power', $key2]);
        } elseif (substr($row['entity'], -9, 9) === 'Condition') {
            $key2 = $this->Html->link((string)$key2, ['controller' => 'History', 'action' => 'condition', $key2]);
        }
    } elseif ($row['entity'] === 'Item') {
        $key1 = $this->Html->link((string)$key1, ['controller' => 'History', 'action' => 'item', $key1]);
    } elseif ($row['entity'] === 'Power') {
        $key1 = $this->Html->link((string)$key1, ['controller' => 'History', 'action' => 'power', $key1]);
    } elseif ($row['entity'] === 'Condition') {
        $key1 = $this->Html->link((string)$key1, ['controller' => 'History', 'action' => 'condition', $key1]);
    }

    echo "<tr>\n"
        . '<td>' . $checkbox . "</td>\n"
        . '<td class="not-on-mobile">' . $row['status']->label() . ($duplicate ? ' (D)' : '') . "</td>\n"
        . '<td class="not-on-mobile">' . $row['creator_id'] . "</td>\n"
        . '<td>' . $row['entity'] . ' ' . $key1 . ' ' . $key2 . "</td>\n"
        . '<td>' . $row['modified'] . "</td>\n"
        . "</tr>\n";
}

?>
</table>
<?php

if ($isInfobalie) {
    echo $this->Form->end();
}
