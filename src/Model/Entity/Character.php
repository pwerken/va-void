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
			[ 'comments'    => 'referee'
			];

	protected $_hidden =
			[ 'id'
			, 'belief_id'
			, 'group_id'
			, 'faction_id'
			, 'world_id'
			];

	protected $_compact = [ 'player_id' , 'chin' , 'name', 'status' ];

	public static function soulpathValues()
	{
		static $data = null;
		if(is_null($data))
			$data = ['BO', 'LI', 'LU', 'MA', 'MO', 'NO', 'NY', 'RA', 'SO'];
		return $data;
	}

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
