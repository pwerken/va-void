<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CharactersSkill extends Entity {

	protected $_hidden =
		[ 'character_id'
		, 'skill_id'
		];

}
