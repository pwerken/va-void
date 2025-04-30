<?php
declare(strict_types=1);

namespace App\Model\Entity;

class Condition extends Entity
{
    protected array $_compact = [ 'id', 'name', 'deprecated' ];
}
