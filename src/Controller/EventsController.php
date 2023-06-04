<?php
declare(strict_types=1);

namespace App\Controller;

class EventsController
    extends AppController
{
    use \App\Controller\Trait\Add;      // PUT /events
    use \App\Controller\Trait\View;     // GET /events/{id}
    use \App\Controller\Trait\Edit;     // PUT /events/{id}
    use \App\Controller\Trait\Delete;   // DELETE /events/{id}

    // GET /events
    public function index(): void
    {
        $query = $this->Events->find();
        $this->doRawIndex($query, 'Events', '/events/', 'id');
    }
}
