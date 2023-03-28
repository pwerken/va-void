<?php
declare(strict_types=1);

namespace App\Controller;

class WorldsController
    extends AppController
{
    use \App\Controller\Trait\View;
    use \App\Controller\Trait\Edit;

    public function index()
    {
        $query = $this->Worlds->find();
#       $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Worlds', '/worlds/', 'id');
    }
}
