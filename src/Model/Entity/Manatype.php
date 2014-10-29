<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Manatype Entity.
 */
class Manatype extends Entity {

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
	protected $_accessible = [
		'name' => true,
		'skills' => true,
	];

}
