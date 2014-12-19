<?php
namespace App\Model\Entity;

class Skill extends JsonEntity {

	protected $_accessible =
		[ 'name' => true
		, 'cost' => true
		, 'manatype_id' => true
		, 'mana_amount' => true
		, 'sort_order' => true
		, 'manatype' => true
		, 'characters' => true
		, '_joinData' => true
		];

	protected $_json_short =
		[ 'id'
		, 'name'
		, 'cost'
		, 'mana_amount'
		, 'manatype'
		];

}
