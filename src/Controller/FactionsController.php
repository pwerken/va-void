<?php
declare(strict_types=1);

namespace App\Controller;

class FactionsController
    extends AppController
{

    public function index()
    {
        $query = $this->Factions->find();
#       $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Factions', '/factions/', 'id');
    }
}
