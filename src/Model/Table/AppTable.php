<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;

abstract class AppTable
	extends Table
{

	protected $_contain = [ ];

	public function findWithContain(Query $query, array $options = [])
	{
		if(!empty($this->_contain))
			$query->contain($this->_contain);

		return $query;
	}

}
