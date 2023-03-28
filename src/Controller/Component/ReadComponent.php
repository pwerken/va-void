<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

class ReadComponent
    extends Component
{
    protected $components = ['Authorization'];

    public function action(int $id)
    {
        $model = $this->getController()->loadModel();

        $obj = $model->findWithContainById($id)->firstOrFail();
        $this->Authorization->authorize($obj);

        $this->getController()->set('_serialize', $obj);
    }
}
