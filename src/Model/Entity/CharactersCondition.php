<?php
declare(strict_types=1);

namespace App\Model\Entity;

class CharactersCondition extends Entity
{
    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['expiry', 'character', 'condition']);
        $this->setHidden(['character_id', 'condition_id'], true);
    }

    public function getUrl(): string
    {
        return $this->getRelationUrl('character', 'condition');
    }
}
