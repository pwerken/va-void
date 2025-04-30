<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\ViewTrait;

/**
 * @property \App\Model\Table\AttributesTable $Attributes;
 */
class AttributesController extends Controller
{
    use ViewTrait; // GET /attributes/{id}

    /**
     * GET /attributes
     */
    public function index(): void
    {
        $query = $this->Attributes->find()
                    ->select(['Attributes.id', 'Attributes.name'], true);
        $this->doRawIndex($query, 'Attributes', '/attributes/', 'id');
    }
}
