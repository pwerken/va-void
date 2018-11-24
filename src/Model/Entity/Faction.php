<?php
namespace App\Model\Entity;

class Faction
	extends AppEntity
{

	protected $_compact = [ 'id', 'name' ];

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->showFieldAuth('characters', ['read-only']);
	}
}
