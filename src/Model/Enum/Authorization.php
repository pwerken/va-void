<?php
declare(strict_types=1);

namespace App\Model\Enum;

enum Authorization: int
{
    case Owner = 0;
    case Player = 1;
    case ReadOnly = 2;
    case Referee = 3;
    case Infobalie = 4;
    case EventControl = 5;
    case Super = 6;

    public function hasAuth(Authorization $want): bool
    {
        return $want->value > 0 && $want->value <= $this->value;
    }
}
