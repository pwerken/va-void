<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

use App\Error\Exception\ValidationException;

class EditComponent
    extends Component
{
    protected $components = ['Authorization'];

    public function action(int|array $id): void
    {
        $controller = $this->getController();
        $model = $controller->fetchModel();

        $obj = $model->get($id);
        $this->Authorization->authorize($obj, 'edit');
        $this->Authorization->applyScope($obj, 'accesible');

        $data = $controller->getRequest()->getData();
        $obj = $model->patchEntity($obj, $data, ['associated' => []]);

        if (!$model->save($obj, ['checkExisting' => false])) {
            throw new validationexception($obj);
        }

        $controller->set('_serialize', $obj->refresh());
    }
}
