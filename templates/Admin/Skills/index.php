<?php
declare(strict_types=1);
/**
 * @var \Cake\View\View $this
 * @var string $since
 * @var int $and
 * @var array $selected
 * @var array $characters
 * @var array $skills
 */
?>
<h3>Lookup Players with Skill</h3>
<?php

$optionsSkills = [];
$optionsSkills['type'] = 'select';
$optionsSkills['label'] = 'Skills';
$optionsSkills['value'] = $selected;
$optionsSkills['options'] = $skills;
$optionsSkills['multiple'] = true;

$optionsAnd = [];
$optionsAnd['type'] = 'radio';
$optionsAnd['label'] = false;
$optionsAnd['value'] = $and;
$optionsAnd['hiddenField'] = false;
$optionsAnd['options'] = [0 => 'one of the skills', 1 => 'all the skills'];

$optionsSince = [];
$optionsSince['type'] = 'date';
$optionsSince['label'] = 'Modified since';
$optionsSince['value'] = $since;
$optionsSince['maxlength'] = 10;

echo $this->Form->create(null, ['type' => 'GET'])
    . $this->Form->control('skills', $optionsSkills)
    . '<br/>' . PHP_EOL
    . $this->Form->control('and', $optionsAnd)
    . '<br/>' . PHP_EOL
    . $this->Form->control('since', $optionsSince)
    . $this->Form->button('Select')
    . $this->Form->end();

if (count($characters) == 0) {
    return;
}

?>
<p>
Found <?= count($characters) ?> characters.
</p>

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
    $link = [ 'controller' => 'History', 'action' => 'character', $c['player_id'] , $c['chin']]
    ?>
<tr>
    <td><?= $c['player_id'] . '-' . $c['chin'] ?></td>
    <td><?= $this->Html->link($c['name'], $link) ?></td>
    <td><?= $c['status'] ?></td>
    <td><?= $c['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss') ?></td>
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
