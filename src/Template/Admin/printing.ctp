<h3>Printing queue</h3>
<?php

echo $this->Form->create();
echo $this->Form->button(__('Delete selected'));

?>
<table>
<tr>
	<th>ID</th>
	<th>Status</th>
	<th>Type</th>
	<th>Key #1</th>
	<th>Key #2</th>
</tr>
<?php

foreach($printing as $row) {
	echo "<tr>\n"
		."<td>$row[id] ";

	if($row['status'] == 'Queued') {
		echo $this->Form->checkbox('delete[]'
			, ['value' => $row['id'], 'hiddenField' => false]);
	}

	echo "</td>\n"
		."<td>$row[status]</td>\n"
		."<td>$row[entity]</td>\n"
		."<td>$row[key1]</td>\n"
		."<td>$row[key2]</td>\n"
		."</tr>\n";
}

?>
</table>
</form>
