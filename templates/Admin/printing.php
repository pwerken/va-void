<h3>Printing queue</h3>
<?php

$uniq = [];
$duplicates = 0;
$queued = 0;
foreach($printing as $row) {
	if($row['status'] != 'Queued')
		continue;
	$queued ++;
	$key = $row['entity'] . "_" . $row['key1'] . "_" . $row['key2'];
	if(isset($uniq[$key])) {
		$duplicates++;
	}
	$uniq[$key] = $row['id'];
}

if($queued > 0)
	echo "&nbsp;Queue size : $queued";
if($duplicates > 0)
	echo " ($duplicates duplicates)";

echo $this->Form->create();

$role = (!isset($user) ? '' : $user['role']);
switch($role) {
case 'Super':
case 'Infobalie':
	echo $this->Form->button(__('Delete selected'));
	echo '<span style="padding-left:100px;"></span>';
	echo $this->Html->link('Single-sided pdf', '/admin/printing/single'
		, ['class' => 'button']);
	echo "&nbsp;";
	echo $this->Html->link('Double-sided pdf', '/admin/printing/double'
		, ['class' => 'button']);
	echo "LET OP: klikken = status naar 'Printed' voor hele queue";

	break;
default:
}

?>
<table>
<tr>
	<th>ID</th>
	<th>Status</th>
	<th>Type</th>
	<th>Key #1</th>
	<th>Key #2</th>
	<th>Created</th>
	<th>Creator</th>
	<th>Modified</th>
</tr>
<?php

foreach($printing as $row) {
	echo "<tr>\n"
		."<td>" . $row['id'];
	if($row['status'] == 'Queued') {
		$key = $row['entity'] . "_" . $row['key1'] . "_" . $row['key2'];
		if($uniq[$key] != $row['id']) {
			$checked = ' checked';
		} else {
			$checked = '';
		}
		echo ' <input type="checkbox" name="delete[]" value="'.$row['id'].'"'.$checked.'>';
	}
	if(!is_null($row['created'])) {
		$row['created'] = $row['created']->i18nFormat('yyyy-MM-dd HH:mm:ss');
	}
	if(is_null($row['creator_id'])) {
		$row['creator_id'] = '(??)';
	}
	if(!is_null($row['modified'])) {
		$row['modified'] = $row['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss');
	}

	$key1 = $row['character_str'] ?: $row['key1'];
	$key2 = $row['key2'];
	$link = null;

	if(substr($row['entity'], 0, 9) == 'Character') {
		$key1 = $this->Html->link($key1, '/admin/history/character/'.$key1);
	} elseif($row['entity'] == 'Item') {
		$link = 'item';
	}
	if(substr($row['entity'], -5, 5) == 'Power') {
		$link = 'power';
	} elseif(substr($row['entity'], -9, 9) == 'Condition') {
		$link = 'condition';
	}
	if($link) {
		if(is_null($key2)) {
			$key1 = $this->Html->link($key1, '/admin/history/'.$link.'/'.$key1);
		} else {
			$key2 = $this->Html->link($key2, '/admin/history/'.$link.'/'.$key2);
		}
	}

	echo "</td>\n"
		."<td>" . $row['status'] . "</td>\n"
		."<td>" . $row['entity'] . "</td>\n"
		."<td>" . $key1 . "</td>\n"
		."<td>" . $key2 . "</td>\n"
		."<td>" . $row['created'] . "</td>\n"
		."<td>" . $row['creator_id'] . "</td>\n"
		."<td>" . $row['modified'] . "</td>\n"
		."</tr>\n";
}

?>
</table>
</form>

<?php

echo $this->Form->end();
