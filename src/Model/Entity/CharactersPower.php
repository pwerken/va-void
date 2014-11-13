<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CharactersPower Entity.
 */
class CharactersPower extends Entity {

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
	protected $_accessible = [
		'expiry' => true,
		'character' => true,
		'power' => true,
	];

}
