<?php
declare(strict_types=1);

namespace App\Controller\Component;

use App\Error\Exception\ValidationException;
use Cake\Controller\Component;

class EditComponent extends Component
{
    public function action(int|array $id): void
    {
        $controller = $this->getController();
        $model = $controller->fetchTable();

        $obj = $model->get($id);
        $controller->Authorization->authorize($obj, 'edit');
        $controller->Authorization->applyScope($obj, 'accesible');

        $data = $controller->getRequest()->getData();
        $obj = $model->patchEntity($obj, $data, ['associated' => []]);

        if (!$model->save($obj, ['checkExisting' => false])) {
            throw new validationexception($obj);
        }

        $controller->set('_serialize', $obj->refresh());
    }
}
