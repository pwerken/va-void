<?php
namespace App\Model\Entity;

class Condition
	extends AppEntity
{

	protected $_showAuth = [ 'cs_text' => 'read-only' ];

	protected $_compact = [ 'id', 'name' ];

}
