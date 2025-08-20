<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Enum\CharacterStatus;
use Cake\ORM\Locator\LocatorAwareTrait;

class Character extends Entity
{
    use LocatorAwareTrait;

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

        $this->setCompact(['player_id', 'chin', 'name', 'status']);

        $this->setVirtual(['faction', 'xp_available']);

        $this->setHidden(['id', 'xp_available'], true);
        $this->setHidden(['faction_id', 'faction_object'], true);
    }

    public function getUrl(): string
    {
        return '/' . $this->getBaseUrl() . '/' . $this->get('player_id') . '/' . $this->get('chin');
    }

    protected function _getFaction(): ?string
    {
        return $this->get('faction_object')?->name;
    }

    protected function _getXpAvailable(): float
    {
        if (empty($this->get('skills')) && $this->getSource()) {
            $this->getTableLocator()->get($this->getSource())->loadInto($this, ['Skills']);
        }

        $used = 0;
        foreach ($this->get('skills') as $skill) {
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
