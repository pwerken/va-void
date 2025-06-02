<?php
declare(strict_types=1);

namespace App\Test\Fixture;

enum TestAccount: int
{
    case Player = 1;
    case ReadOnly = 2;
    case Referee = 3;
    case Infobalie = 4;
    case Super = 5;
}
