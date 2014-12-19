<?php
namespace App\Model\Entity;

class CharactersSpell extends JsonEntity {

	protected $_accessible =
		[ 'level' => true
		, 'character' => true
		, 'spell' => true
		];

	protected $_json_short =
		[ 'level' ];
}
