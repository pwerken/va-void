<?php
namespace App\Model\Entity;

class CharactersSkill extends JsonEntity {

	protected $_accessible =
		[ 'character' => true
		, 'skill' => true
		];

}
