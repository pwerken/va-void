<?php
declare(strict_types=1);

namespace App\Controller;

class GroupsController
    extends AppController
{

    public function index()
    {
        $query = $this->Groups->find();
#       $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Groups', '/groups/', 'id');
    }

    public function view($id)
    {
        $belief = $this->Groups->findWithContainById($id)->first();
#        $this->Authorization->authorize($belief);

        $this->set('_serialize', $belief);
    }
}
