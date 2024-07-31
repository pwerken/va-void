<?php
namespace App\Lammy;

class PowerLammy
	extends ConditionLammy
{

	public function draw($side, $data = NULL)
	{
		$data = [];
		$data['type'] = 'Power';
		$data['key']  = 'POIN';
		$data['id']   = $this->entity->id;
		$data['name'] = $this->entity->name;
		$data['text'] = $this->entity->player_text;
		$data['plin'] = NULL;
		$data['char'] = NULL;
		$data['expiry'] = NULL;

		parent::draw($side, $data);
	}

}
