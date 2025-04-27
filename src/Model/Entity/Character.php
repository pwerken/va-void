<?php
namespace App\Model\Entity;

class Character
    extends AppEntity
{

    protected $_defaults =
            [ 'xp'          => 15
            , 'status'      => 'inactive'
            , 'belief'      => '-'
            , 'group'       => '-'
            , 'faction_id'  => 1
            , 'world'       => '-'
            ];

    public function __construct($properties = [], $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['player_id', 'chin', 'name', 'status']);

        $this->setVirtual(['teacher', 'faction']);

        $this->setHidden(['id'], true);
        $this->setHidden(['faction_id', 'faction_object'], true);
    }

    public static function soulpathValues(): array
    {
        static $data = null;
        if(is_null($data))
            $data = ['BO', 'LI', 'LU', 'MA', 'MO', 'NO', 'NY', 'RA', 'SO', 'TA'];
        return $data;
    }

    public static function statusValues(): array
    {
        static $data = null;
        if(is_null($data))
            $data = ['dead', 'inactive', 'active'];
        return $data;
    }

    public function getUrl(): string
    {
        return '/'.$this->getBaseUrl().'/'.$this->player_id.'/'.$this->chin;
    }

    protected function _getFaction(): ?string
    {
        if (is_null($this->faction_object))
            return null;
        return $this->faction_object->name;
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
        return str_replace(',', '.', $xp);
    }
}
