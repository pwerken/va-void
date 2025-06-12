<?php
declare(strict_types=1);

namespace App\Model\Entity;

class Item extends Entity
{
    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['expiry', 'character', 'deprecated'], true);
        $this->setVirtual(['plin', 'chin']);
        $this->setHidden(['character_id'], true);
    }

    protected function _getPlin(): ?int
    {
        return $this->get('character')?->player_id;
    }

    protected function _getChin(): ?int
    {
        return $this->get('character')?->chin;
    }
}
