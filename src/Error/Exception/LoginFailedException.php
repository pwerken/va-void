<?php
declare(strict_types=1);

namespace App\Error\Exception;

use Cake\Http\Exception\UnauthorizedException;

class LoginFailedException extends UnauthorizedException
{
}
