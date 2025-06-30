<?php
declare(strict_types=1);

namespace App\Model\Entity;

class CharactersPower extends Entity
{
    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['expiry', 'character', 'power']);
        $this->setHidden(['character_id', 'power_id'], true);
    }

    public function getUrl(): string
    {
        return $this->getRelationUrl('character', 'power');
    }
}
