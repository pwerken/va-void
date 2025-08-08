<?php
declare(strict_types=1);

namespace App\Model\Entity;

class Manatype extends Entity
{
    protected array $_compact = [ 'id', 'name', 'deprecated' ];
}
