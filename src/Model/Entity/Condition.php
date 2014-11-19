<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Condition Entity.
 */
class Condition extends Entity {

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
	protected $_accessible = [
		'name' => true,
		'player_text' => true,
		'cs_text' => true,
		'characters' => true,
		'_joinData' => true,
	];

}
