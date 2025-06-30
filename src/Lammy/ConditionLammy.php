<?php
declare(strict_types=1);

namespace App\Lammy;

class ConditionLammy extends CharactersConditionLammy
{
    public function draw(int $side, ?array $data = null): void
    {
        $data = [];
        $data['type'] = 'Condition';
        $data['key'] = 'COIN';
        $data['id'] = $this->entity->get('id');
        $data['name'] = $this->entity->get('name');
        $data['text'] = $this->entity->get('player_text');
        $data['plin'] = null;
        $data['char'] = null;
        $data['expiry'] = null;

        parent::draw($side, $data);
    }

    protected function _drawFront(array $data): void
    {
        parent::_drawFront($data);

        $this->inMargin('printed by: ' . $this->who);
    }
}
