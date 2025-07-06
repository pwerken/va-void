<h3>Database Migrations</h3>

<p>Database migrations are done using the command line interface, see
<code>./bin/cake migrations --help</code> for more details.<br/>
This pages gives a basic overview of the migration files and their status.</p>

<p>
<samp>Status
Migration ID&nbsp;&nbsp;
Migration Name</samp><br/>
<?php
foreach ($migrations as [$status, $id, $name]) {
    echo '<samp>'
        . str_replace(' ', '&nbsp', str_pad($status, 6, ' ', STR_PAD_BOTH))
        . '  ' . $id
        . '  ' . $name
        . "</samp><br/>\n";
}
?>
</p>
