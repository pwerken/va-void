<?php
declare(strict_types=1);
/**
 * @var \Cake\View\View $this
 * @var array $migrations
 */
?>
<h3>Database Migrations</h3>

<p>Database migrations are done using the command line interface, see
<code>./bin/cake migrations --help</code> for more details.<br/>
This pages gives a basic overview of the migration files and their status.</p>

<table>
    <tr>
        <th>Status</th>
        <th>Migration ID</th>
        <th>Migration Name</th>
    </tr>
<?php
foreach ($migrations as [$status, $id, $name]) {
    echo '<tr>'
        . '<td>' . $status . '</td>'
        . '<td>' . $id . '</td>'
        . '<td>' . $name . '</td>'
        . "</tr>\n";
}
?>
</table>
