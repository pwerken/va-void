<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CharactersSpell
	extends Entity
{

	protected $_hidden = [ 'character_id', 'spell_id' ];

}
