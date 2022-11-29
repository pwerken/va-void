<?php
declare(strict_types=1);

namespace App\Controller;

class SpellsController
    extends AppController
{

    public function index()
    {
        $query = $this->Spells->find();
#       $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Spells', '/spells/', 'id');
    }
}
