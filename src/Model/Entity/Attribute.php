<?php
namespace App\Model\Entity;

class Attribute
	extends AppEntity
{

	protected $_compact = [ 'id', 'name', 'code' ];

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->showFieldAuth('name', ['read-only']);
	}
}
