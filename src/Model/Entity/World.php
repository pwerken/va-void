<?php
namespace App\Model\Entity;

class World extends JsonEntity {

	protected $_accessible =
		[ 'name' => true
		, 'characters' => true
		];

	protected $_json_short =
		[ 'id', 'name' ];

}
