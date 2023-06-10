<?php
declare(strict_types=1);

namespace App\Controller;

class FactionsController
    extends AppController
{
    use \App\Controller\Traits\View;     // GET /factions/{id}

    // GET /factions
    public function index(): void
    {
        $query = $this->Factions->find();
        $this->doRawIndex($query, 'Factions', '/factions/', 'id');
    }
}
