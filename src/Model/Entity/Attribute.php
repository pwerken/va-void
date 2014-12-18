<?php
namespace App\Model\Entity;

class Attribute extends JsonEntity {

	protected $_accessible =
		[ 'name' => true
		, 'category' => true
		, 'code' => true
		, 'items' => true
		];

	protected $_json_short =
		[ 'id'
		, 'name'
		];

}
