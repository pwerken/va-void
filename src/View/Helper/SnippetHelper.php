<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use App\Model\Entity\Character;
use App\Model\Entity\Player;
#use App\Model\Entity\*;

/**
 * Snippet helper
 */
class SnippetHelper extends Helper {

/**
 * Default configuration.
 *
 * @var array
 */
	protected $_defaultConfig = [];

	public function date($obj, $field) {
		if(!$obj->has($field))
			return null;

		return $obj->$field->format('d-m-Y');
	}

	public function character(Character $character) {
		$url = '/api/characters/'.$character->player_id.'/'.$character->chin;

		return	[ 'url' => $url
				, 'plin' => $character->player_id
				, 'chin' => $character->chin
				, 'name' => $character->name
				];
	}
	public function player(Player $player) {
		$url = '/api/players/'.$player->id;

		return	[ 'url' => $url
				, 'plin' => $player->id
				, 'full_name' => $player->full_name
				];
	}
}
