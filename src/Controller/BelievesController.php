<?php
declare(strict_types=1);

namespace App\Controller;

class BelievesController
    extends AppController
{
    use \App\Controller\Traits\Add;      // PUT /believes
    use \App\Controller\Traits\View;     // GET /believes/{id}
    use \App\Controller\Traits\Edit;     // PUT /believes/{id}
    use \App\Controller\Traits\Delete;   // DELETE /believes/{id}

    // GET /believes
    public function index(): void
    {
        $query = $this->Believes->find();
        $this->doRawIndex($query, 'Believes', '/believes/', 'id');
    }
}
