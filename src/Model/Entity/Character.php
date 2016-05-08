<?php
namespace App\Model\Entity;

class Character
	extends AppEntity
{

	protected $_defaults =
			[ 'xp'          => 15
			, 'status'      => 'inactive'
			, 'belief_id'   =>  1
			, 'group_id'    =>  1
			, 'faction_id'  =>  1
			, 'world_id'    =>  1
			];

	protected $_editAuth =
			[ 'player_id'   => 'infobalie'
			, 'chin'        => 'infobalie'
			, 'xp'          => 'infobalie'
			, 'status'      => 'referee'
			, 'comments'    => 'referee'
			];

	protected $_showAuth =
			[ 'id'          => 'super'
			, 'comments'    => 'referee'
			];

	public static function statusValues()
	{
		static $data = null;
		if(is_null($data))
			$data = ['dead', 'inactive', 'active'];
		return $data;
	}

	public function getUrl()
	{
		return '/'.$this->getBaseUrl().'/'.$this->player_id.'/'.$this->chin;
	}

}
