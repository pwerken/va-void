<?php
declare(strict_types=1);

namespace App\Model\Entity;

class CharactersImbue extends Entity
{
    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['type', 'times', 'character', 'imbue']);
        $this->setHidden(['character_id', 'imbue_id'], true);
    }

    public function getUrl(): string
    {
        $a = $this->get('character')->getUrl();
        $b = $this->get('imbue')->getUrl();

        return $a . '/' . $this->get('type') . substr($b, 1);
    }

    protected function _setType(mixed $value): mixed
    {
        if (isset($this->_defauls['type'])) {
            return $this->_defauls['type'];
        }

        return $value;
    }
}
