<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\CharacterFieldListingTrait;

class GroupsController extends Controller
{
    use CharacterFieldListingTrait;

    /**
     * GET /groups
     */
    public function index(): void
    {
        $this->_createListing('group', 'Group');
    }
}
