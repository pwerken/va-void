<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Item Entity.
 */
class Item extends Entity {

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
	protected $_accessible = [
		'name' => true,
		'dscription' => true,
		'player_text' => true,
		'cs_text' => true,
		'character_id' => true,
		'expiry' => true,
		'character' => true,
		'attributes' => true,
	];

}
