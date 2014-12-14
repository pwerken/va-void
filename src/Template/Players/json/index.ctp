<?php
$output = [];
$output['url'] = '/api/players';
$output['list'] = [];
foreach($players as $player) {
	$output['list'][] = $this->Snippet->player($player);
}

echo $this->Snippet->json_encode($output);
