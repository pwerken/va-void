<?php
declare(strict_types=1);

namespace App\Model\Enum;

use App\Model\Enum\Traits\ValueAsLabelTrait;
use Cake\Database\Type\EnumLabelInterface;

enum CharacterStatus: string implements EnumLabelInterface
{
    use ValueAsLabelTrait;

    case Active = 'active';
    case Inactive = 'inactive';
    case Dead = 'dead';
}
