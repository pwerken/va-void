<?php
declare(strict_types=1);

namespace App\Model\Enum;

use App\Model\Enum\Traits\ValueAsLabelTrait;
use Cake\Database\Type\EnumLabelInterface;

enum PlayerRole: string implements EnumLabelInterface
{
    use ValueAsLabelTrait;

    case Player = 'Player';
    case ReadOnly = 'Read-only';
    case Referee = 'Referee';
    case Infobalie = 'Infobalie';
    case EventControl = 'Event Control';
    case Super = 'Super';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function toAuth(): Authorization
    {
        return match ($this) {
            self::Player => Authorization::Player,
            self::ReadOnly => Authorization::ReadOnly,
            self::Referee => Authorization::Referee,
            self::Infobalie => Authorization::Infobalie,
            self::EventControl => Authorization::EventControl,
            self::Super => Authorization::Super,
        };
    }
}
