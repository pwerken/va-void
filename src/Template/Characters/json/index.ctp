<?php
$output = [];
$output['url'] = '/api/characters';
$output['list'] = [];
foreach($characters as $character) {
	// FIXME: or only provide the url to the player object?
	$output['list'][] = $this->Snippet->character($character)
		+ [ 'player' => $this->Snippet->player($character->player) ];
}

echo $this->Snippet->json_encode($output);
