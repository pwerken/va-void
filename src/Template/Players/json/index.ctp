<?php
use Cake\Core\Configure;

$url = '/api/players';

$output = array();
$output['url'] = $url;
foreach($players as $player) {
	$output['data'][] = $this->Snippet->player($player);
}

echo json_encode($output, Configure::read('debug') ? JSON_PRETTY_PRINT : 0);
