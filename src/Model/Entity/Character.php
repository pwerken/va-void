<?php
namespace App\Model\Entity;

class Character
    extends AppEntity
{

    protected $_defaults =
            [ 'xp'          => 15
            , 'status'      => 'inactive'
            , 'belief   '   =>  '-'
            , 'group'       =>  '-'
            , 'faction_id'  =>  1
            , 'world'       =>  '-'
            ];

    public function __construct($properties = [], $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['player_id', 'chin', 'name', 'status']);

        $this->setVirtual(['teacher', 'faction']);

        $this->setHidden(['id'], true);
        $this->setHidden(['faction_id', 'faction_object'], true);
    }

    public static function soulpathValues()
    {
        static $data = null;
        if(is_null($data))
            $data = ['BO', 'LI', 'LU', 'MA', 'MO', 'NO', 'NY', 'RA', 'SO', 'TA'];
        return $data;
    }

    public static function statusValues()
    {
        static $data = null;
        if(is_null($data))
            $data = ['dead', 'inactive', 'active'];
        return $data;
    }

    public function getUrl()
    {
        return '/'.$this->getBaseUrl().'/'.$this->player_id.'/'.$this->chin;
    }

    protected function _getFaction()
    {
        if (is_null($this->faction_object))
            return null;
        return $this->faction_object->name;
    }

    protected function _setXp($xp)
    {
        return str_replace(',', '.', $xp);
    }
}
