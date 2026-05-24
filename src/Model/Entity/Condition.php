<?php
declare(strict_types=1);

namespace App\Model\Entity;

/**
 * @property int                                $coin
 * @property string                             $name
 * @property string                             $player_text
 * @property ?string                            $referee_notes
 * @property ?string                            $notes
 * @property ?int                               $manatype_id
 * @property ?int                               $mana_amount
 * @property bool                               $deprecated
 * @property ?\Cake\I18n\DateTime               $created
 * @property ?int                               $creator_id
 * @property ?\Cake\I18n\DateTime               $modified
 * @property ?int                               $modifier_id
 *
 * Relations:
 * @property ?list<\App\Model\Entity\Character> $characters
 * @property ?\App\Model\Entity\Manatype        $manatype
 */
class Condition extends Entity
{
    protected array $_compact = [ 'coin', 'name', 'deprecated' ];

    public function getUrl(): string
    {
        return '/' . $this->getBaseUrl() . '/' . $this->coin;
    }
}
