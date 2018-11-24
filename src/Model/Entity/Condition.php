<?php
namespace App\Model\Entity;

class Condition
	extends AppEntity
{

	protected $_compact = [ 'id', 'name' ];

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->showFieldAuth('cs_text', ['read-only']);
	}
}
