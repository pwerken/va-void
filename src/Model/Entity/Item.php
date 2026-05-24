<?php
declare(strict_types=1);

namespace App\Model\Entity;

/**
 * @property int                                $itin
 * @property string                             $name
 * @property ?string                            $description
 * @property ?string                            $player_text
 * @property ?string                            $referee_notes
 * @property ?string                            $notes
 * @property ?int                               $manatype_id
 * @property ?int                               $mana_amount
 * @property ?int                               $character_id
 * @property ?\Cake\I18n\DateTime               $expiry
 * @property bool                               $deprecated
 * @property ?\Cake\I18n\DateTime               $created
 * @property ?int                               $creator_id
 * @property ?\Cake\I18n\DateTime               $modified
 * @property ?int                               $modifier_id
 *
 * Virtual:
 * @property ?int                               $plin
 * @property ?int                               $chin
 *
 * Relations:
 * @property ?\App\Model\Entity\Character       $character
 * @property ?\App\Model\Entity\Manatype        $manatype
 */
class Item extends Entity
{
    protected array $_compact = ['itin', 'name', 'expiry', 'character', 'deprecated'];

    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setVirtual(['plin', 'chin']);
        $this->setHidden(['character_id'], true);
    }

    public function getUrl(): string
    {
        return '/' . $this->getBaseUrl() . '/' . $this->itin;
    }

    protected function _getPlin(): ?int
    {
        return $this->character?->plin;
    }

    protected function _getChin(): ?int
    {
        return $this->character?->chin;
    }
}
