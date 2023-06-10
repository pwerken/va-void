<?php
declare(strict_types=1);

namespace App\Controller;

class WorldsController
    extends AppController
{
    use \App\Controller\Traits\Add;      // PUT /worlds
    use \App\Controller\Traits\View;     // GET /worlds/{id}
    use \App\Controller\Traits\Edit;     // PUT /worlds/{id}
    use \App\Controller\Traits\Delete;   // DELETE /worlds/{id}

    // GET /worlds
    public function index(): void
    {
        $query = $this->Worlds->find();
#       $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Worlds', '/worlds/', 'id');
    }
}
