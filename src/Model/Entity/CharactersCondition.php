<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CharactersCondition extends Entity {

	protected $_hidden =
		[ 'character_id'
		, 'condition_id'
		];

}
