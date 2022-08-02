<?php
declare(strict_types=1);

namespace App\Controller;

class BelievesController
    extends AppController
{

    public function index()
    {
        $query = $this->Believes->find();
#        $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Believes', '/believes/', 'id');
    }

    public function view($id)
    {
        $belief = $this->Believes->findWithContainById($id)->first();
#        $this->Authorization->authorize($belief);

        $this->set('_serialize', $belief);
    }
}
