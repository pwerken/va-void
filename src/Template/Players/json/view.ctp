<?php
use Cake\Core\Configure;

$url = '/api/players/'.$player->id;

$output = array();
$output['url'] = $url;
$output['plin'] = $player->id;
$output['full_name'] = $player->full_name;
$output['first_name'] = $player->first_name;
$output['insertion'] = $player->insertion;
$output['last_name'] = $player->last_name;
$output['gender'] = $player->gender;
$output['date_of_birth'] = $this->Snippet->date($player, 'date_of_birth');

$output['characters'] = array();
$output['characters']['url'] = $url.'/characters/';
foreach($player->characters as $character) {
	$output['characters']['data'][] = $this->Snippet->character($character);
}

echo json_encode($output, Configure::read('debug') ? JSON_PRETTY_PRINT : 0);
