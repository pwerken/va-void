<?php
namespace App\Model\Entity;

class Condition
	extends AppEntity
{

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->showFieldAuth('referee_notes', ['read-only']);
		$this->showFieldAuth('notes', ['read-only']);
	}
}
