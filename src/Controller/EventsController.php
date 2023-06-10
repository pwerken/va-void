<?php
declare(strict_types=1);

namespace App\Controller;

class EventsController
    extends AppController
{
    use \App\Controller\Traits\Add;      // PUT /events
    use \App\Controller\Traits\View;     // GET /events/{id}
    use \App\Controller\Traits\Edit;     // PUT /events/{id}
    use \App\Controller\Traits\Delete;   // DELETE /events/{id}

    // GET /events
    public function index(): void
    {
        $query = $this->Events->find();
        $this->doRawIndex($query, 'Events', '/events/', 'id');
    }
}
