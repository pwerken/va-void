<?php
use Cake\Core\Configure;

$url = '/api/players';

$output = array();
$output['url'] = $url;
foreach($players as $player) {
	$output['data'][] =
		[ 'url' => $url.'/'.$player->id
		, 'plin' => $player->id
		, 'name' => $player->full_name
		];
}

echo json_encode($output, Configure::read('debug') ? JSON_PRETTY_PRINT : 0);
