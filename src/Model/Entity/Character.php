<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Character Entity.
 */
class Character extends Entity {

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
	protected $_accessible = [
		'player_id' => true,
		'chin' => true,
		'name' => true,
		'xp' => true,
		'faction_id' => true,
		'belief_id' => true,
		'group_id' => true,
		'world_id' => true,
		'status' => true,
		'comments' => true,
		'player' => true,
		'faction' => true,
		'belief' => true,
		'group' => true,
		'world' => true,
		'items' => true,
		'conditions' => true,
		'powers' => true,
		'skills' => true,
		'spells' => true,
		'_joinData' => true,
	];

	protected function _getDisplayName() {
		return $this->_properties['player_id']
			. '-' . $this->_properties['chin']
			. ': ' . $this->_properties['name'];
	}
}
