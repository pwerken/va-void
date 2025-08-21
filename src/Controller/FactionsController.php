<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\AddTrait;
use App\Controller\Traits\DeleteTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\ViewTrait;

class FactionsController extends Controller
{
    use AddTrait; // PUT /factions
    use DeleteTrait; // DELETE /factions/{id}
    use EditTrait; // PUT /factions/{id}
    use ViewTrait; // GET /factions/{id}

    /**
     * GET /factions
     */
    public function index(): void
    {
        $query = $this->fetchTable()->find();
        $this->doRawIndex($query, 'Factions', '/factions/', 'id');
    }
}
