<?php
namespace App\Model\Entity;

class Character extends JsonEntity {

	protected $_accessible =
		[ 'player_id' => true
		, 'chin' => true
		, 'name' => true
		, 'xp' => true
		, 'faction_id' => true
		, 'faction' => true
		, 'belief_id' => true
		, 'belief' => true
		, 'group_id' => true
		, 'group' => true
		, 'world_id' => true
		, 'world' => true
		, 'status' => true
		, 'comments' => true
		, 'items' => true
		, 'conditions' => true
		, 'powers' => true
		, 'skills' => true
		, 'spells' => true
		, 'player' => true
		, '_joinData' => true
		];

	protected $_hidden =
		[ 'faction_id'
		, 'belief_id'
		, 'group_id'
		, 'world_id'
		];

	protected $_json_aliases =
		[ 'player_id' => 'plin' ];

	protected $_json_short =
		[ 'player_id', 'chin', 'name' ];

	public function jsonUrl() {
		return '/api/characters/'.$this->player_id.'/'.$this->chin;
	}

	protected function _getDisplayName() {
		return $this->_properties['player_id']
			. '-' . $this->_properties['chin']
			. ': ' . $this->_properties['name'];
	}
}
