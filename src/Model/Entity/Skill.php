<?php
namespace App\Model\Entity;

class Skill
	extends AppEntity
{

	protected $_compact = [ 'id', 'name', 'cost', 'mana_amount', 'manatype' ];

}
