<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\CharacterFieldListingTrait;

class WorldsController extends Controller
{
    use CharacterFieldListingTrait;

    /**
     * GET /worlds
     */
    public function index(): void
    {
        $this->_createListing('world', 'World');
    }
}
