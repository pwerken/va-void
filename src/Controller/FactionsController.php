<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\AddTrait;
use App\Controller\Traits\DeleteTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\IndexTrait;
use App\Controller\Traits\ViewTrait;

// @phpcs:disable PSR12.Traits.UseDeclaration.NoBlankLineAfterUse
class FactionsController extends Controller
{
    use IndexTrait; // GET /factions
    use AddTrait; // PUT /factions
    use ViewTrait; // GET /factions/{id}
    use EditTrait; // PUT /factions/{id}
    use DeleteTrait; // DELETE /factions/{id}
}
