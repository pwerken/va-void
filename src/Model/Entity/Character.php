<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Enum\CharacterStatus;
use Cake\ORM\TableRegistry;

class Character extends Entity
{
    protected array $_defaults = [
        'xp' => 15,
        'status' => CharacterStatus::Inactive,
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

        $this->setHidden(['id', 'mana', 'xp_available'], true);
        $this->setHidden(['faction_id', 'faction_object'], true);
    }

    public function getUrl(): string
    {
        return '/' . $this->getBaseUrl() . '/' . $this->get('plin') . '/' . $this->get('chin');
    }

    protected function _getFaction(): ?string
    {
        return $this->get('faction_object')?->name;
    }

    protected function _getMana(): array
    {
        $sources = $this->get('skills');
        $sources = array_merge($sources, $this->get('imbues'));
        $sources = array_merge($sources, $this->get('powers'));
        $sources = array_merge($sources, $this->get('conditions'));
        $sources = array_merge($sources, $this->get('items'));

        $mana = [];
        $manatypes = TableRegistry::getTableLocator()->get('Manatypes')->find();
        foreach ($manatypes as $manatype) {
            $mana[$manatype->get('name')] = 0;
        }

        foreach ($sources as $source) {
            $type = $source->get('manatype')?->get('name');
            if (is_null($type)) {
                continue;
            }
            $times = $source->_joinData?->get('times') ?? 1;
            $mana[$type] += $times * $source->get('mana_amount');
        }

        return $mana;
    }

    protected function _getXpAvailable(): float
    {
        $used = 0;
        $skills = $this->get('skills') ?? [];
        foreach ($skills as $skill) {
            $times = $skill->_joinData->get('times');
            $used += $times * $skill->get('cost');
        }

        return $this->get('xp') - $used;
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
