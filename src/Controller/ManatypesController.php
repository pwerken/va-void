<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\IndexTrait;
use App\Controller\Traits\ViewTrait;

// @phpcs:disable PSR12.Traits.UseDeclaration.NoBlankLineAfterUse
class ManatypesController extends Controller
{
    use IndexTrait; // GET /manatypes
    use ViewTrait; // GET /manatypes/{id}
}
