<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\AddTrait;
use App\Controller\Traits\DeleteTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\ViewTrait;

class PlayersController extends Controller
{
    use AddTrait; // PUT /players
    use ViewTrait; // GET /players/{plin}
    use EditTrait; // PUT /players/{plin}
    use DeleteTrait; // DELETE /players/{plin}

    /**
     * GET /players
     */
    public function index(): void
    {
        $query = $this->fetchTable()->find();
        $this->Authorization->applyScope($query);

        $this->set('_serialize', $query->all());
    }
}
