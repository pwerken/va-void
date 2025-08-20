<?php
declare(strict_types=1);

namespace App\Model\Enum;

use App\Model\Enum\Traits\ValueAsLabelTrait;
use Cake\Database\Type\EnumLabelInterface;

enum LammyStatus: string implements EnumLabelInterface
{
    use ValueAsLabelTrait;

    case Queued = 'Queued';
    case Printing = 'Printing';
    case Printed = 'Printed';
    case Failed = 'Failed';
}
