<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class AttributesItem
	extends Entity
{

	protected $_hidden = [ 'attribute_id', 'item_id' ];

}
