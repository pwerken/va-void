<?php
declare(strict_types=1);

namespace App\Model\Entity;

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
        return '/' . $this->getBaseUrl() . '/' . $this->get('itin');
    }

    protected function _getPlin(): ?int
    {
        return $this->get('character')?->get('plin');
    }

    protected function _getChin(): ?int
    {
        return $this->get('character')?->get('chin');
    }
}
