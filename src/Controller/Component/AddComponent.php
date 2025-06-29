<?php
declare(strict_types=1);

namespace App\Controller\Component;

use App\Error\Exception\ValidationException;
use Cake\Controller\Component;

class AddComponent extends Component
{
    public function action(bool $checkAuthorize = true): void
    {
        $controller = $this->getController();
        $model = $controller->fetchTable();

        $obj = $model->newEmptyEntity();
        if ($checkAuthorize) {
            $controller->Authorization->authorize($obj, 'add');
        }
        $controller->Authorization->applyScope($obj, 'accessible');

        $data = $controller->getRequest()->getData();
        $obj = $model->patchEntity($obj, $data, ['associated' => []]);

        if (!$model->save($obj, ['checkExisting' => false])) {
            throw new ValidationException($obj);
        }

        $obj = $obj->refresh();

        $response = $controller->getResponse()
                        ->withLocation($obj->getUrl())
                        ->withStatus(201);
        $controller->setResponse($response);
        $controller->set('_serialize', $obj);
    }
}
