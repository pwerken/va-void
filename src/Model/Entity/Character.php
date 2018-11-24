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

	public function __construct($properties = [], $options = [])
	{
		parent::__construct($properties, $options);

		$this->setCompact(['player_id', 'chin', 'name', 'status']);

		$this->setVirtual(['teacher', 'belief', 'faction', 'group', 'world']);

		$this->addHidden(['id']);
		$this->addHidden(['belief_id', 'belief_object']);
		$this->addHidden(['faction_id', 'faction_object']);
		$this->addHidden(['group_id', 'group_object']);
		$this->addHidden(['world_id', 'world_object']);

		$this->editFieldAuth('player_id', ['infobalie']);
		$this->editFieldAuth('chin', ['infobalie']);
		$this->editFieldAuth('xp', ['referee']);
		$this->editFieldAuth('status', ['referee']);
		$this->editFieldAuth('comments', ['referee']);

		$this->showFieldAuth('comments', ['read-only']);
	}

	public static function soulpathValues()
	{
		static $data = null;
		if(is_null($data))
			$data = ['BO', 'LI', 'LU', 'MA', 'MO', 'NO', 'NY', 'RA', 'SO', 'TA'];
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
