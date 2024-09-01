<?php
declare(strict_types=1);

namespace App\Controller;

class GroupsController
    extends AppController
{
    use \App\Controller\Traits\CharacterFieldListing;

    // GET /groups
    public function index(): void
    {
        $this->_createListing('group', 'Group');
    }
}
