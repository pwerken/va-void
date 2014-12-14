<?php

$output = array();
$output['url'] = '/api/players';
foreach($players as $player) {
	$output['data'][] = $this->Snippet->player($player);
}

echo $this->Snippet->json_encode($output);
