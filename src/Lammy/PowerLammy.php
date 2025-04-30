<?php
declare(strict_types=1);

namespace App\Lammy;

class PowerLammy extends ConditionLammy
{
    public function draw(int $side, ?array $data = null): void
    {
        $data = [];
        $data['type'] = 'Power';
        $data['key'] = 'POIN';
        $data['id'] = $this->entity->id;
        $data['name'] = $this->entity->name;
        $data['text'] = $this->entity->player_text;
        $data['plin'] = null;
        $data['char'] = null;
        $data['expiry'] = null;

        parent::draw($side, $data);
    }
}
