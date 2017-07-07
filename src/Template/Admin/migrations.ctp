<h3>Database Migrations</h3>

<p>Database migrations are done using the command line interface, see
<code>./bin/cake migrations --help</code> for more details.<br/>
This pages gives a basic overview of the migration files and their status.</p>

<table>
<tr>
	<th>Status</th>
	<th>ID</th>
	<th>Name</th>
</tr>
<?php
foreach ($migrations as $migration) :
?>
<tr>
	<td><?= $migration['status'] ?></td>
	<td><?= $migration['id'] ?></td>
	<td><?= $migration['name'] ?></td>
</tr>
<?php endforeach; ?>
</table>
