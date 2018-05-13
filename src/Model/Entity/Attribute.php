<?php
namespace App\Model\Entity;

class Attribute
	extends AppEntity
{

	protected $_showAuth = [ 'name'	=> 'read-only' ];

	protected $_compact = [ 'id', 'name', 'code' ];

}
