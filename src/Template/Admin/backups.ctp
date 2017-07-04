<h3>Database Backups</h3>

<p>Backup management is done using the command line interface, see
<code>./bin/cake backup --help</code> for more details.<br/>
This pages gives a basic overview of the database backups present on the
server.</p>

<table cellspacing="0" cellpadding="0">
<tr><th>Filename</th><th>Size</th><th>Datetime</th></tr>

<?php

foreach ($backups as $backup)
{
	list($file, $size, $date) = $backup;
    printf( "<tr><td>%s</td><td>%s</td><td>%s</td></tr>\n"
			, $file, $size, $date);
}

?>
</table>
