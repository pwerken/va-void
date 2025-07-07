<h3>Database Backups</h3>

<p>Backup management is done using the command line interface, see
<code>./bin/cake backup --help</code> for more details.<br/>
This pages gives a basic overview of the database backups present on the
server.</p>

<p>
<samp>Size&nbsp;&nbsp;&nbsp;&nbsp;
Filename</samp><br/>
<?php
foreach ($backups as [$file, $size, $date]) {
    echo '<samp>' . $size . ' ' . $file . "</samp><br/>\n";
}
?>
</p>
