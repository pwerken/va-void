<?php
namespace App\Model\Entity;

class Character
	extends AppEntity
{

	protected $_defaults =
			[ 'xp'          => 15
			, 'belief_id'   =>  1
			, 'group_id'    =>  1
			, 'faction_id'  =>  1
			, 'world_id'    =>  1
			];

	protected $_editAuth =
			[ 'player_id'   => 'referee'
			, 'chin'        => 'referee'
			, 'xp'          => 'referee'
			, 'status'      => 'referee'
			, 'comments'    => 'referee'
			];

	protected $_showAuth =
			[ 'id'          => 'super'
			, 'comments'    => 'referee'
			];

}
