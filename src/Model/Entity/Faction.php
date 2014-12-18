<?php
namespace App\Model\Entity;

class Faction extends JsonEntity {

	protected $_accessible =
		[ 'name' => true
		, 'characters' => true
		];

	protected $_json_short =
		[ 'id', 'name' ];

}
