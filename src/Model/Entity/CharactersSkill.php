<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CharactersSkill Entity.
 */
class CharactersSkill extends Entity {

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
	protected $_accessible = [
		'character' => true,
		'skill' => true,
	];

}
