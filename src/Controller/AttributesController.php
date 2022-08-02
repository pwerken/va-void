<?php
declare(strict_types=1);

namespace App\Controller;

class AttributesController
    extends AppController
{

    public function index()
    {
        $query = $this->Attributes->find();
#        $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Attributes', '/attributes/', 'id');
    }

    public function view($id)
    {
        $attribute = $this->Attributes->findWithContainById($id)->first();
#        $this->Authorization->authorize($attribute);

        $this->set('_serialize', $attribute);
    }
}
