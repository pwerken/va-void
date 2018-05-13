<?php
namespace App\Model\Entity;

class Group
	extends AppEntity
{

	protected $_showAuth = [ 'characters' => 'read-only' ];

	protected $_compact = [ 'id', 'name' ];

}
