<?php
declare(strict_types=1);

namespace App\Controller;

class AttributesController
    extends AppController
{
    use \App\Controller\Traits\View; // GET /attributes/{id}

    // GET /attributes
    public function index(): void
    {
        $query = $this->Attributes->find()
                    ->select(['Attributes.id', 'Attributes.name'], true);
        $this->doRawIndex($query, 'Attributes', '/attributes/', 'id');
    }
}
