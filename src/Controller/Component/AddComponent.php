<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

use App\Error\Exception\ValidationException;

class AddComponent
    extends Component
{
    protected $components = ['Authorization'];

    public function action(bool $checkAuthorize = true): void
    {
        $controller = $this->getController();
        $model = $controller->loadModel();

        $obj = $model->newEmptyEntity();
        if($checkAuthorize) {
            $this->Authorization->authorize($obj, 'add');
        }
        $this->Authorization->applyScope($obj, 'accesible');

        $data = $controller->getRequest()->getData();
        $obj = $model->patchEntity($obj, $data, ['associated' => []]);

        if(!$model->save($obj, ['checkExisting' => false])) {
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
