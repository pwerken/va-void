<?php
namespace App\Lammy;

class ConditionLammy
	extends CharactersConditionLammy
{

	public function draw($side, $data = NULL)
	{
		$data = [];
		$data['type'] = 'Condition';
		$data['key']  = 'COIN';
		$data['id']   = $this->entity->id;
		$data['name'] = $this->entity->name;
		$data['text'] = $this->entity->player_text;
		$data['plin'] = NULL;
		$data['char'] = NULL;
		$data['expiry'] = NULL;

		parent::draw($side, $data);
	}

    protected function _drawFront($data)
    {
        parent::_drawFront($data);

        $this->inMargin('printed by: ' . $this->who);
    }
}
