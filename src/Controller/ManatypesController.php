<?php
declare(strict_types=1);

namespace App\Controller;

class ManatypesController
    extends AppController
{
    use \App\Controller\Traits\View; // GET /manatypes/{id}

    // GET /manatypes
    public function index(): void
    {
        $query = $this->Manatypes->find()
                    ->select(['Manatypes.id', 'Manatypes.name'], true);
        $this->doRawIndex($query, 'Manatypes', '/manatypes/', 'id');
    }
}
