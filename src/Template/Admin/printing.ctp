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

echo $this->Form->create();

$role = (!isset($user) ? '' : $user['role']);
switch($role) {
case 'Super':
case 'Infobalie':
	echo $this->Form->button(__('Delete selected'));
	break;
default:
}

if($queued > 0)
	echo "&nbsp;Queued : $queued";
if($duplicates > 0)
	echo " ($duplicates duplicates)";

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
	echo "</td>\n"
		."<td>" . $row['status'] . "</td>\n"
		."<td>" . $row['entity'] . "</td>\n"
		."<td>" . $row['key1'] . "</td>\n"
		."<td>" . $row['key2'] . "</td>\n"
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
