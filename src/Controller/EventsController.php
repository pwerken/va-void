<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\AddTrait;
use App\Controller\Traits\DeleteTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\IndexTrait;
use App\Controller\Traits\ViewTrait;

// @phpcs:disable PSR12.Traits.UseDeclaration.NoBlankLineAfterUse
class EventsController extends Controller
{
    use IndexTrait; // GET /events
    use AddTrait; // PUT /events
    use ViewTrait; // GET /events/{id}
    use EditTrait; // PUT /events/{id}
    use DeleteTrait; // DELETE /events/{id}
}
