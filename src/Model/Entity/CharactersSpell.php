<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CharactersSpell Entity.
 */
class CharactersSpell extends Entity {

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
	protected $_accessible = [
		'level' => true,
		'character' => true,
		'spell' => true,
	];

}
