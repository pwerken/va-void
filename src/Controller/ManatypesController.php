<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\ViewTrait;

/**
 * @property \App\Model\Table\ManatypesTable $Manatypes;
 */
class ManatypesController extends Controller
{
    use ViewTrait; // GET /manatypes/{id}

    /**
     * GET /manatypes
     */
    public function index(): void
    {
        $query = $this->Manatypes->find()
                    ->select(['id', 'name'], true)
                    ->where(['deprecated' => 0]);
        $this->doRawIndex($query, 'Manatypes', '/manatypes/', 'id');
    }
}
