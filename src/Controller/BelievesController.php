<?php
declare(strict_types=1);

namespace App\Controller;

class BelievesController
    extends AppController
{
    use \App\Controller\Trait\Add;      // PUT /believes
    use \App\Controller\Trait\View;     // GET /believes/{id}
    use \App\Controller\Trait\Edit;     // PUT /believes/{id}
    use \App\Controller\Trait\Delete;   // DELETE /believes/{id}

    // GET /believes
    public function index(): void
    {
        $query = $this->Believes->find();
        $this->doRawIndex($query, 'Believes', '/believes/', 'id');
    }
}
