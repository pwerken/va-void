<?php
declare(strict_types=1);

namespace App\Lammy;

class PowerLammy extends CharactersConditionLammy
{
    public function draw(int $side, ?array $data = null): void
    {
        $data = [];
        $data['type'] = 'Power';
        $data['key'] = 'POIN';
        $data['id'] = $this->entity->get('poin');
        $data['name'] = $this->entity->get('name');
        $data['text'] = $this->entity->get('player_text');
        $data['plin'] = null;
        $data['char'] = null;
        $data['expiry'] = null;

        parent::draw($side, $data);
    }
}
