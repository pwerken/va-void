<?php
namespace App\Model\Entity;

class Group
	extends AppEntity
{

	protected $_showAuth =
			[ 'characters'      => 'referee'
			];

	protected $_compact = [ 'id', 'name' ];

}
