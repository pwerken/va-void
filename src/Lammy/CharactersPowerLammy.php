<?php
namespace App\Lammy;

class CharactersPowerLammy
	extends CharactersConditionLammy
{

	public function draw($side)
	{
		$data = [];
		$data['type'] = 'Power';
		$data['key']  = 'POIN';
		$data['id']   = $this->entity->power_id;
		$data['name'] = $this->entity->power->name;
		$data['text'] = $this->entity->power->player_text;

		parent::draw($side, $data);
	}

}
