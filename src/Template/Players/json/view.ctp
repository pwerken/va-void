<?php
use Cake\Core\Configure;

$url = '/api/players/'.$player->id;

$output = array();
$output['url'] = $url;
$output['plin'] = $player->id;
$output['name'] = $player->full_name;
$output['first_name'] = $player->first_name;
$output['insertion'] = $player->insertion;
$output['last_name'] = $player->last_name;
$output['gender'] = $player->gender;
if($player->has('date_of_birth'))
	$output['date_of_birth'] = $player->date_of_birth->format('d-m-Y');
else
	$output['date_of_birth'] = null;

$output['characters'] = array();
foreach($player->characters as $character) {
	$output['characters'][] =
		[ 'url' => '/api/characters/'.$character->player_id.'/'.$character->chin
		, 'plin' => $character->player_id
		, 'chin' => $character->chin
		, 'name' => $character->name
		];
}

echo json_encode($output, Configure::read('debug') ? JSON_PRETTY_PRINT : 0);
