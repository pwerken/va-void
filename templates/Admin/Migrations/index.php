<?php
declare(strict_types=1);

/**
 * @var \App\View\AdminView $this
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
        . '<td>' . h($status) . '</td>'
        . '<td>' . h($id) . '</td>'
        . '<td>' . h($name) . '</td>'
        . "</tr>\n";
}
?>
</table>
