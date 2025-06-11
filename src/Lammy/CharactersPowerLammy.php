<?php
declare(strict_types=1);

namespace App\Lammy;

class CharactersPowerLammy extends CharactersConditionLammy
{
    public function draw(int $side, ?array $data = null): void
    {
        $data = [];
        $data['type'] = 'Power';
        $data['key'] = 'POIN';
        $data['id'] = $this->entity->power_id;
        $data['name'] = $this->entity->power->name;
        $data['text'] = $this->entity->power->player_text;
        $data['plin'] = $this->entity->character->player_id
                        . ' - ' . $this->entity->character->chin;
        $data['char'] = $this->entity->character->name;

        $expiry = $this->entity->expiry ?: 'Until death';
        if (!is_string($expiry)) {
            $expiry = (string)$expiry;
        }
        $data['expiry'] = $expiry;

        parent::draw($side, $data);
    }
}
