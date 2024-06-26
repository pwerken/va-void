<h3>Entity History</h3>
<?php

use Cake\ORM\TableRegistry;

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

function format_v($k, $v)
{
	if(is_null($v))
		return '<em>NULL</em>';
	if(is_bool($v))
		return '<em>'.($v?'true':'false').'</em>';

	if($k !== 'character_id')
		return $v;

	$char = TableRegistry::get('Characters')->get($v);
	return $v.' = '.$char->player_id.'/'.$char->chin.' '.$char->name;
}

foreach($list as $cur)
{
	switch($cur->get('state')) {
	case 'added':		$color = ' style="color:green"'; break;
	case 'removed':		$color = ' style="color:red"';	break;
	case 'modified':	$color = ' style="color:blue"';	break;
	default:			$color = '';
	}

    if($plin && ($plin == $cur->get('modifier_id'))) {
        $bgcolor = ' style="background-color:yellow"';
    } else {
        $bgcolor = '';
    }

	$data = $cur->get('data');
	if (!is_null($data))
		$data = json_decode($data, true);

	$related = $cur->relation();
	if(is_null($related) && isset($data['name']))
		$related = $data['name'];
	if(!is_null($related))
		$related = '<strong>'.$related.'</strong> ';

	$prefix = "<span$bgcolor><samp>"
        . str_pad($cur->modifiedString(), 19, '_', STR_PAD_BOTH) . " "
        . $cur->modifierString() . "</samp> "
		. $cur->keyString() . "<span$color> " . $related;
    $postfix = "</span></span><br/>\n";

	switch($cur->get('state')) {
	case 'added':
	case 'removed':
		if(empty($data)) {
			echo $prefix . $postfix;
			break;
		}
		foreach($data as $k => $v) {
			$v = format_v($k, $v);
			echo $prefix . "<em>$k</em>: $v" . $postfix;
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
			$v = format_v($k, $v);
			echo $prefix . "<em>$k</em>: $v" . $postfix;
		}
		break;
	default:
		break;
	}
}

