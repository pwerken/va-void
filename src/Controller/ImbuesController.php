<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\AddTrait;
use App\Controller\Traits\DeleteTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\IndexTrait;
use App\Controller\Traits\ViewTrait;

// @phpcs:disable PSR12.Traits.UseDeclaration.NoBlankLineAfterUse
class ImbuesController extends Controller
{
    use IndexTrait; // PUT /imbues
    use AddTrait; // PUT /imbues
    use ViewTrait; // GET /imbues/{id}
    use EditTrait; // PUT /imbues/{id}
    use DeleteTrait; // DELETE /imbues/{id}
}
