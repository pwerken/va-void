<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AttributesItem Entity.
 */
class AttributesItem extends Entity {

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
	protected $_accessible = [
		'attribute' => true,
		'item' => true,
	];

}
