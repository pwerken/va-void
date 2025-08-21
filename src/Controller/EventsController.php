<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\AddTrait;
use App\Controller\Traits\DeleteTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\ViewTrait;

class EventsController extends Controller
{
    use AddTrait; // PUT /events
    use DeleteTrait; // DELETE /events/{id}
    use EditTrait; // PUT /events/{id}
    use ViewTrait; // GET /events/{id}

    /**
     * GET /events
     */
    public function index(): void
    {
        $query = $this->fetchTable()->find();
        $this->doRawIndex($query, 'Events', '/events/', 'id');
    }
}
