<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Attribute Entity.
 */
class Attribute extends Entity {

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
	protected $_accessible = [
		'name' => true,
		'category' => true,
		'code' => true,
		'items' => true,
	];

}
