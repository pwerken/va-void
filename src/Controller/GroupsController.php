<?php
declare(strict_types=1);

namespace App\Controller;

class GroupsController
    extends AppController
{
    use \App\Controller\Traits\Add;      // PUT /groups
    use \App\Controller\Traits\View;     // GET /groups/{id}
    use \App\Controller\Traits\Edit;     // PUT /groups/{id}
    use \App\Controller\Traits\Delete;   // DELETE /groups/{id}

    // GET /groups
    public function index(): void
    {
        $query = $this->Groups->find();
#       $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Groups', '/groups/', 'id');
    }
}
