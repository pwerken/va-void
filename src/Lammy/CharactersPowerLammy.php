<?php
declare(strict_types=1);

namespace App\Lammy;

class CharactersPowerLammy extends CharactersConditionLammy
{
    public function draw(int $side, ?array $data = null): void
    {
        $power = $this->entity->get('power');
        $character = $this->entity->get('character');

        $data = [];
        $data['type'] = 'Power';
        $data['key'] = 'POIN';
        $data['id'] = $this->entity->get('power_id');
        $data['name'] = $power->get('name');
        $data['text'] = $power->get('player_text');
        $data['plin'] = $character->get('plin') . ' - ' . $character->get('chin');
        $data['char'] = $character->get('name');

        $expiry = $this->entity->get('expiry') ?: 'Until death';
        if (!is_string($expiry)) {
            $expiry = (string)$expiry;
        }
        $data['expiry'] = $expiry;

        parent::draw($side, $data);
    }
}
