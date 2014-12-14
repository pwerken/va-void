<?php

$output = array();
$output['url'] = '/api/players/'.$player->id;
$output['plin'] = $player->id;
$output['full_name'] = $player->full_name;
$output['first_name'] = $player->first_name;
$output['insertion'] = $player->insertion;
$output['last_name'] = $player->last_name;
$output['gender'] = $player->gender;
$output['date_of_birth'] = $this->Date->dmy($player, 'date_of_birth');

$output['characters'] = array();
$output['characters']['url'] = $output['url'].'/characters/';
foreach($player->characters as $character) {
	$output['characters']['data'][] = $this->Snippet->character($character);
}

echo $this->Snippet->json_encode($output);
