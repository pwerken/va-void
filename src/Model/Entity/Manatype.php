<?php
namespace App\Model\Entity;

class Manatype extends JsonEntity {

	protected $_accessible =
		[ 'name' => true
		, 'skills' => true
		];

	public function jsonShort() {
		return $this->get('name');
	}

}
