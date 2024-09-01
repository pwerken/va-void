<?php
declare(strict_types=1);

namespace App\Controller;

class BelievesController
    extends AppController
{
    use \App\Controller\Traits\CharacterFieldListing;

    // GET /believes
    public function index(): void
    {
        $this->_createListing('belief', 'Belief');
    }
}
