<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\AddTrait;
use App\Controller\Traits\DeleteTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\IndexTrait;
use App\Controller\Traits\ViewTrait;

// @phpcs:disable PSR12.Traits.UseDeclaration.NoBlankLineAfterUse
class ManatypesController extends Controller
{
    use IndexTrait; // GET /skills
    use AddTrait; // PUT /skills
    use ViewTrait; // GET /skills/{id}
    use EditTrait; // PUT /skills/{id}
    use DeleteTrait; // DELETE /skills/{id}
}
