<?php
namespace App\Model\Entity;

class CharactersPower extends JsonEntity {

	protected $_accessible =
		[ 'expiry' => true
		, 'character' => true
		, 'power' => true
		];

	protected $_json_short =
		[ 'expiry' ];

}
