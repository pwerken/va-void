<h3>Database Backups</h3>

<p>Backup management is done using the command line interface, see
<code>./bin/cake backup --help</code> for more details.<br/>
This pages gives a basic overview of the database backups present on the
server.</p>

<table>
    <tr>
        <th>Size</th>
        <th>Filename</th>
    </tr>
<?php
foreach ($backups as [$file, $size, $date]) {
    echo '<tr>'
        . '<td>' . $size . '</td>'
        . '<td>' . $file . '</td>'
        . "</tr>\n";
}
?>
</table>
