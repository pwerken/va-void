<?php
declare(strict_types=1);

namespace App\Controller;

class WorldsController
    extends AppController
{

    public function index()
    {
        $query = $this->Worlds->find();
#       $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Worlds', '/worlds/', 'id');
    }
}
