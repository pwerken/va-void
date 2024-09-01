<?php
declare(strict_types=1);

namespace App\Controller;

class WorldsController
    extends AppController
{
    use \App\Controller\Traits\CharacterFieldListing;

    // GET /worlds
    public function index(): void
    {
        $this->_createListing('world', 'World');
    }
}
