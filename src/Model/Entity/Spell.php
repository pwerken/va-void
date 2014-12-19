<?php
namespace App\Model\Entity;

class Spell extends JsonEntity {

	protected $_accessible =
		[ 'name' => true
		, 'short' => true
		, 'spiritual' => true
		, 'characters' => true
		];

	protected $_json_short =
		[ 'id', 'name', 'short' ];

}
