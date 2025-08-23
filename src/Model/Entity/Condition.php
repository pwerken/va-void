<?php
declare(strict_types=1);

namespace App\Model\Entity;

class Condition extends Entity
{
    protected array $_compact = [ 'coin', 'name', 'deprecated' ];

    public function getUrl(): string
    {
        return '/' . $this->getBaseUrl() . '/' . $this->get('coin');
    }
}
