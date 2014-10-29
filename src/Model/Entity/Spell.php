<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Spell Entity.
 */
class Spell extends Entity {

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
	protected $_accessible = [
		'name' => true,
		'short' => true,
		'spiritual' => true,
		'characters' => true,
	];

}
