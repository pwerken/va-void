<?php
declare(strict_types=1);

namespace App\Controller;

class GroupsController
    extends AppController
{
    use \App\Controller\Trait\Add;      // PUT /groups
    use \App\Controller\Trait\View;     // GET /groups/{id}
    use \App\Controller\Trait\Edit;     // PUT /groups/{id}
    use \App\Controller\Trait\Delete;   // DELETE /groups/{id}

    // GET /groups
    public function index(): void
    {
        $query = $this->Groups->find();
#       $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Groups', '/groups/', 'id');
    }
}
