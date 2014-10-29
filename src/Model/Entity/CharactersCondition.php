<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CharactersCondition Entity.
 */
class CharactersCondition extends Entity {

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
	protected $_accessible = [
		'expiry' => true,
		'character' => true,
		'condition' => true,
	];

}
