<?php
declare(strict_types=1);

namespace App\Model\Entity;

/**
 * @property int    $id
 * @property string $name
 * @property bool   $deprecated
 */
class Manatype extends Entity
{
    protected array $_compact = [ 'id', 'name', 'deprecated' ];
}
