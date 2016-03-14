<?php
namespace App\Model\Entity;

class Item
	extends AppEntity
{

	protected $_showAuth =
			[ 'character_id'    => 'super'
			, 'cs_text'         => 'referee'
			, 'attributes'      => 'referee'
			];

	protected $_virtual = [ 'plin', 'chin' ];

	protected function _getPlin()
	{
		return @$this->character->player_id;
	}

	protected function _getChin()
	{
		return @$this->character->chin;
	}
}
