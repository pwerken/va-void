<?php
declare(strict_types=1);

namespace App\Controller;

class SpellsController
    extends AppController
{
    use \App\Controller\Traits\View;     // GET /spells/{id}

    // GET /spells
    public function index(): void
    {
        $query = $this->Spells->find();
        $this->doRawIndex($query, 'Spells', '/spells/', 'id');
    }
}
