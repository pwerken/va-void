<?php
declare(strict_types=1);

namespace App\Model\Entity;

/**
 * @property int                                $character_id
 * @property int                                $condition_id
 * @property ?\Cake\I18n\DateTime               $expiry
 * @property ?\Cake\I18n\DateTime               $modified
 * @property ?int                               $modifier_id
 *
 * Relations:
 * @property ?\App\Model\Entity\Character       $character
 * @property ?\App\Model\Entity\Condition       $condition
 */
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
