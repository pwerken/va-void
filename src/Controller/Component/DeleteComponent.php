<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\Response;

use App\Error\Exception\ValidationException;

class DeleteComponent
    extends Component
{
    protected $components = ['Authorization'];

    public function action(int|array $id): void
    {
        $controller = $this->getController();
        $model = $controller->fetchModel();

        $obj = $model->get($id);
        $this->Authorization->authorize($obj, 'delete');

        if(!$model->delete($obj)) {
            throw new ValidationException($obj);
        }

        $response = $controller->getResponse()
                        ->withStatus(204);
        $controller->setResponse($response);
        $controller->set('_serialize', null);
    }
}
