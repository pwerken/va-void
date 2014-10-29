<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * World Entity.
 */
class World extends Entity {

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
