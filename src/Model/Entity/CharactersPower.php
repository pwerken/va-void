<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CharactersPower extends Entity {

	protected $_hidden =
		[ 'character_id'
		, 'power_id'
		];

}
