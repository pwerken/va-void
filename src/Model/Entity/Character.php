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
			, 'belief_id', 'belief_object'
			, 'faction_id', 'faction_object'
			, 'group_id', 'group_object'
			, 'world_id', 'world_object'
			];

	protected $_compact = [ 'player_id', 'chin', 'name', 'status' ];

	protected $_virtual = [ 'teacher', 'belief', 'faction', 'group', 'world' ];

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

	protected function _getBelief()
	{
		return $this->belief_object->name;
	}

	protected function _getFaction()
	{
		return $this->faction_object->name;
	}

	protected function _getGroup()
	{
		return $this->group_object->name;
	}

	protected function _getWorld()
	{
		return $this->world_object->name;
	}

	protected function _setXp($xp)
	{
		return str_replace(',', '.', $xp);
	}
}
