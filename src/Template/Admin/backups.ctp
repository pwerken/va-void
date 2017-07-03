<h3>Database Backups</h3>

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
