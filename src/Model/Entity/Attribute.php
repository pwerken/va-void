<?php
namespace App\Model\Entity;

class Attribute
	extends AppEntity
{

	protected $_showAuth =
			[ 'name'			=> 'referee'
			];

	protected $_compact = [ 'id', 'name', 'code' ];

}
