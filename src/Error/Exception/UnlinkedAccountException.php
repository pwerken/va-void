<?php
declare(strict_types=1);

namespace App\Error\Exception;

use Cake\Http\Exception\ForbiddenException;

class UnlinkedAccountException extends ForbiddenException
{
}
