<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Faction Entity.
 */
class Faction extends Entity {

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
	protected $_accessible = [
		'name' => true,
		'characters' => true,
	];

}
