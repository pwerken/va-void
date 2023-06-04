<?php
declare(strict_types=1);

namespace App\Controller;

class WorldsController
    extends AppController
{
    use \App\Controller\Trait\Add;      // PUT /worlds
    use \App\Controller\Trait\View;     // GET /worlds/{id}
    use \App\Controller\Trait\Edit;     // PUT /worlds/{id}
    use \App\Controller\Trait\Delete;   // DELETE /worlds/{id}

    // GET /worlds
    public function index(): void
    {
        $query = $this->Worlds->find();
#       $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Worlds', '/worlds/', 'id');
    }
}
