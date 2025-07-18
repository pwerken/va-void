<?php
declare(strict_types=1);
/**
 * @var \Cake\View\View $this
 * @var string $since
 * @var array $selected
 * @var array $characters
 * @var array $skills
 */
echo '<h3>Lookup Players with Skill</h3>' . PHP_EOL;

echo $this->Form->create(null, ['type' => 'get']);
echo 'Only characters modified since:';
echo $this->Form->text('since', ['value' => $since]);
echo 'Select one or more skills:';
echo $this->Form->select('skills', $skills, ['multiple' => true, 'value' => $selected]);
echo $this->Form->button('Select characters');
echo '&nbsp;<strong>Found ' . count($characters) . '</strong>';
echo $this->Form->end();

if (count($characters) == 0) {
    return;
}

?>
<table>
<tr>
    <th>PLIN</th>
    <th>Character Name</th>
    <th>State</th>
    <th>Last Modified</th>
<?php
foreach ($selected as $s) {
    echo "\t<th>" . $skills[$s] . "</th>\n";
}
?>
</tr>
<?php

foreach ($characters as $c) {
    $link = '/admin/history/character/' . $c['player_id'] . '/' . $c['chin'];
    ?>
<tr>
    <td><?php echo $c['player_id'] . '-' . $c['chin']; ?></td>
    <td><?php echo $this->Html->link($c['name'], $link); ?></td>
    <td><?php echo $c['status']; ?></td>
    <td><?php echo $c['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss'); ?></td>
    <?php
    foreach ($selected as $s) {
        echo "\t<td>";
        if (isset($c['_matchingData'][$s])) {
            echo $c['_matchingData'][$s] . 'x';
        } else {
            echo '&nbsp;';
        }
        echo "</td>\n";
    }
    ?>
</tr>
    <?php
}
?>
</table>
