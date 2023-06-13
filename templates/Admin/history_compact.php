<h3>Entity History</h3>
<?php

$state = [];
for($i = count($list) - 1; $i >= 0; $i--)
{
	$cur = $list[$i];
	$key = $cur->keyString();
	if(!isset($state[$key])) {
		$cur->set('state', 'added');
		$state[$key] = $cur->get('data');
	} else if(is_null($cur->data)) {
		$cur->set('state', 'removed');
		unset($state[$key]);
	} else {
		$cur->set('state', 'modified');
		$cur->set('prev', $state[$key]);
		$state[$key] = $cur->get('data');
	}
}

foreach($list as $cur)
{
	switch($cur->get('state')) {
	case 'added':		$color = ' style="color:green"'; break;
	case 'removed':		$color = ' style="color:red"';	break;
	case 'modified':	$color = ' style="color:blue"';	break;
	default:			$color = '';
	}

	$data = $cur->get('data');
	if (!is_null($data))
		$data = json_decode($data, true);

	$related = $cur->relation();
	if(is_null($related) && isset($data['name']))
		$related = $data['name'];
	if(!is_null($related))
		$related = '<strong>'.$related.'</strong> ';

	$prefix = "<samp>" . str_pad($cur->modifiedString(), 19, '_', STR_PAD_BOTH)
        . " " . $cur->modifierString() . "</samp> "
		. $cur->keyString() . "<span$color> " . $related;

	switch($cur->get('state')) {
	case 'added':
		if(empty($data)) {
			echo $prefix . "</span><br/>\n";
			break;
		}
		foreach($data as $k => $v) {
			if(is_null($v))
				$v = '<em>NULL</em>';
			echo $prefix . "<em>$k</em>: $v</span><br/>\n";
		}
		break;
	case 'modified':
		$prev = json_decode($cur->get('prev'), true);
		foreach($data as $k => $v) {
			if (array_key_exists($k, $prev)) {
				if($v == $prev[$k])
					continue;
			} elseif (is_null($v)) {
				continue;
			}

			if(is_null($v))
				$v = '<em>NULL</em>';
			echo $prefix . "<em>$k</em>: $v</span><br/>\n";
		}
		break;
	case 'removed':
		echo $prefix . "</span><br/>\n";
		break;
	default:
		break;
	}
}

