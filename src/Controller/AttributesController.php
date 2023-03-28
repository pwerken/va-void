<?php
declare(strict_types=1);

namespace App\Controller;

class AttributesController
    extends AppController
{
    use \App\Controller\Trait\View;

    public function index()
    {
        $query = $this->Attributes->find();
#        $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Attributes', '/attributes/', 'id');
    }
}
