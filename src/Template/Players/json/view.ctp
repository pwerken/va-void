<?php
$output = $this->Snippet->player($player);
$output['first_name'] = $player->first_name;
$output['insertion'] = $player->insertion;
$output['last_name'] = $player->last_name;
$output['gender'] = $player->gender;
$output['date_of_birth'] = $this->Date->dmy($player->date_of_birth);

$output['characters']['url'] = $output['url'].'/characters/';
$output['characters']['list'] = [];
foreach($player->characters as $character) {
	$output['characters']['list'][] = $this->Snippet->character($character);
}

echo $this->Snippet->json_encode($output);
