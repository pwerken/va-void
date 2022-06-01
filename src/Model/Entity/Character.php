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
		$this->editFieldAuth('referee_notes', ['referee']);
		$this->editFieldAuth('notes', ['referee']);

		$this->showFieldAuth('referee_notes', ['read-only']);
		$this->showFieldAuth('notes', ['read-only']);
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
		if (is_null($this->belief_object))
			return null;
		return $this->belief_object->name;
	}

	protected function _getFaction()
	{
		if (is_null($this->faction_object))
			return null;
		return $this->faction_object->name;
	}

	protected function _getGroup()
	{
		if (is_null($this->group_object))
			return null;
		return $this->group_object->name;
	}

	protected function _getWorld()
	{
		if (is_null($this->world_object))
			return null;
		return $this->world_object->name;
	}

	protected function _setXp($xp)
	{
		return str_replace(',', '.', $xp);
	}
}
