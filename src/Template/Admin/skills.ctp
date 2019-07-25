<h3>Skills overview</h3>
<?php

echo $this->Form->create();
echo "Select one or more skills:";
echo $this->Form->select('skills', $skills, ['multiple' => true]);
echo $this->Form->button('Select characters');
echo "&nbsp;<strong>Found ".count($characters)."</strong>";

if(count($characters) == 0) {
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
	foreach($this->request->getData('skills') as $s) {
		echo "\t<th>".$skills[$s]."</th>\n";
	}
?>
</tr>
<?php

foreach($characters as $c)
{
	$link = '/admin/history/character/'.$c['player_id'].'/'.$c['chin'];
?>
<tr>
	<td><?php echo $c['player_id'].'-'.$c['chin']; ?></td>
	<td><?php echo $this->Html->link($c['name'], $link); ?></td>
	<td><?php echo $c['status']; ?></td>
	<td><?php echo $c['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss'); ?></td>
<?php
	foreach($this->request->getData('skills') as $s) {
		echo "\t<td>";
		if(isset($c['_matchingData'][$s])) {
			echo $skills[$s];
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
