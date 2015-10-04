<?php
namespace App\Model\Entity;

class CharactersCondition extends JsonEntity {

	protected $_accessible =
		[ 'expiry' => true
		, 'character' => true
		, 'condition' => true
		];

	protected $_hidden =
		[ 'character_id'
		, 'condition_id'
		];

	protected $_json_short =
		[ 'expiry' ];

}
