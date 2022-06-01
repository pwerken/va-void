<h3>Entity History</h3>

<?php

$state = [];
for($i = count($list) - 1; $i >= 0; $i--)
{
	$cur = $list[$i];
	$key = $cur->keyString();
	if(!isset($state[$key])) {
		$cur->set('state', 'added');
		$state[$key] = true;
	} else if(is_null($cur->data)) {
		$cur->set('state', 'removed');
		unset($state[$key]);
	} else {
		$cur->set('state', 'modified');
	}
}

foreach($list as $cur)
{
	$related = $cur->relation();
	if(!is_null($related))
		$related = ' - <em>'.$related.'</em>';

	$data = $cur->get('data');
	if(strlen($data) > 2)
		$data = "<br/>\n" . $cur->get('data');
	else
		$data = '';

	switch($cur->get('state')) {
	case 'added':		$color = ' style="color:green"'; break;
	case 'removed':		$color = ' style="color:red"';	break;
	case 'modified':	$color = ' style="color:blue"';	break;
	default:			$color = '';
	}

	echo "<em$color>" . $cur->modifiedString() . " "
		. $cur->get('state') . " by "
		. $cur->modifierString() . "</em><br/>\n"
		. $cur->keyString() . $related
		. $data . "<br/>\n<br/>\n";
}

?>
</table>
