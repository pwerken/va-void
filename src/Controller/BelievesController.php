<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\CharacterFieldListingTrait;

class BelievesController extends Controller
{
    use CharacterFieldListingTrait;

    /**
     * GET /believes
     */
    public function index(): void
    {
        $this->_createListing('belief', 'Belief');
    }
}
