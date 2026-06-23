<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Enum\CharacterStatus;
use Cake\ORM\TableRegistry;

/**
 * @property int                                $id
 * @property int                                $plin
 * @property int                                $chin
 * @property string                             $name
 * @property float                              $xp
 * @property int                                $faction_id
 * @property ?string                            $belief
 * @property ?string                            $group
 * @property ?string                            $world
 * @property ?\App\Model\Enum\CharacterStatus   $status
 * @property ?string                            $referee_notes
 * @property ?string                            $notes
 * @property ?\Cake\I18n\DateTime               $modified
 * @property ?int                               $modifier_id
 *
 * Virtual:
 * @property string                             $faction
 * @property array<string,int>                  $mana
 * @property float                              $xp_available
 *
 * Relations:
 * @property ?\App\Model\Entity\Player          $player
 * @property ?\App\Model\Entity\Faction         $faction_object
 * @property ?list<\App\Model\Entity\Skill>     $skills
 * @property ?list<\App\Model\Entity\Condition> $conditions
 * @property ?list<\App\Model\Entity\Power>     $powers
 * @property ?list<\App\Model\Entity\Item>      $items
 * @property ?\App\Model\Entity\Teaching        $teacher
 * @property ?list<\App\Model\Entity\Teaching>  $students
 */
class Character extends Entity
{
    protected array $_defaults = [
        'xp' => 15,
        'status' => CharacterStatus::Concept,
        'belief' => '-',
        'group' => '-',
        'faction_id' => 1,
        'world' => '-',
    ];

    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['plin', 'chin', 'name', 'status']);

        $this->setVirtual(['faction', 'mana', 'xp_available']);

        $this->setHidden(['id', 'xp_available'], true);
        $this->setHidden(['faction_id', 'faction_object'], true);
    }

    public function getUrl(): string
    {
        return '/' . $this->getBaseUrl() . '/' . $this->plin . '/' . $this->chin;
    }

    protected function _getFaction(): ?string
    {
        return $this->faction_object?->name;
    }

    protected function _getMana(): array
    {
        $sources = $this->skills ?? [];
        $sources = array_merge($sources, $this->powers ?? []);
        $sources = array_merge($sources, $this->conditions ?? []);
        $sources = array_merge($sources, $this->items ?? []);

        $mana = [];
        $manatypes = TableRegistry::getTableLocator()->get('Manatypes')->find();
        foreach ($manatypes as $manatype) {
            $mana[$manatype->name] = 0;
        }

        foreach ($sources as $source) {
            $type = $source->manatype?->name;
            if (is_null($type)) {
                continue;
            }
            $expiry = $source->expiry ?? $source->get('_joinData')?->expiry;
            if (!is_null($expiry) && $expiry->isPast()) {
                continue;
            }

            $times = $source->get('_joinData')->times ?? 1;
            $mana[$type] += $times * $source->mana_amount;
        }

        return $mana;
    }

    protected function _getXpAvailable(): float
    {
        $used = 0;
        $skills = $this->skills ?? [];
        foreach ($skills as $skill) {
            $times = $skill->get('_joinData')->times;
            $used += $times * $skill->cost;
        }

        return $this->xp - $used;
    }

    protected function _setBelief(mixed $belief): mixed
    {
        return $this->emptyToDefault('belief', $belief);
    }

    protected function _setGroup(mixed $group): mixed
    {
        return $this->emptyToDefault('group', $group);
    }

    protected function _setWorld(mixed $world): mixed
    {
        return $this->emptyToDefault('world', $world);
    }

    protected function _setXp(mixed $xp): mixed
    {
        return str_replace(',', '.', (string)$xp);
    }
}
