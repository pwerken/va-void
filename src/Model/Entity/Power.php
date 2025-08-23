<?php
declare(strict_types=1);

namespace App\Model\Entity;

class Power extends Entity
{
    protected array $_compact = [ 'poin', 'name', 'deprecated' ];

    public function getUrl(): string
    {
        return '/' . $this->getBaseUrl() . '/' . $this->get('poin');
    }
}
