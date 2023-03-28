<?php
declare(strict_types=1);

namespace App\Controller;

class GroupsController
    extends AppController
{
    use \App\Controller\Trait\View;

    public function index()
    {
        $query = $this->Groups->find();
#       $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Groups', '/groups/', 'id');
    }
}
