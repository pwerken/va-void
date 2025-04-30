<?php
declare(strict_types=1);

namespace App\Controller\Component;

use App\Error\Exception\ValidationException;
use Cake\Controller\Component;

class DeleteComponent extends Component
{
    public function action(int|array $id): void
    {
        $controller = $this->getController();
        $model = $controller->fetchTable();

        $obj = $model->get($id);
        $controller->Authorization->authorize($obj, 'delete');

        if (!$model->delete($obj)) {
            throw new ValidationException($obj);
        }

        $response = $controller->getResponse()->withStatus(204);
        $controller->setResponse($response);
        $controller->set('_serialize', null);
    }
}
