<?php
declare(strict_types=1);

namespace App\Model\Entity;

class Character extends Entity
{
    protected array $_defaults = [
        'xp' => 15,
        'status' => 'inactive',
        'belief' => '-',
        'group' => '-',
        'faction_id' => 1,
        'world' => '-',
        'soulpath' => '',
    ];

    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['player_id', 'chin', 'name', 'status']);

        $this->setVirtual(['faction']);

        $this->setHidden(['id'], true);
        $this->setHidden(['faction_id', 'faction_object'], true);
    }

    public static function soulpathValues(): array
    {
        static $data = null;
        if (is_null($data)) {
            $data = ['BO', 'LI', 'LU', 'MA', 'MO', 'NO', 'NY', 'RA', 'SO', 'TA'];
        }

        return $data;
    }

    public static function statusValues(): array
    {
        static $data = null;
        if (is_null($data)) {
            $data = ['dead', 'inactive', 'active'];
        }

        return $data;
    }

    public function getUrl(): string
    {
        return '/' . $this->getBaseUrl() . '/' . $this->get('player_id') . '/' . $this->get('chin');
    }

    protected function _getFaction(): ?string
    {
        return $this->get('faction_object')?->name;
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
