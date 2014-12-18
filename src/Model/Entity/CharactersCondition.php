<?php
namespace App\Model\Entity;

class CharactersCondition extends JsonEntity {

	protected $_accessible =
		[ 'expiry' => true
		, 'character' => true
		, 'condition' => true
		];

	protected $_json_short =
		[ 'expiry' ];

}
