<?php
namespace App\Model\Entity;

class Belief
	extends AppEntity
{

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->showFieldAuth('characters', ['read-only']);
	}
}
