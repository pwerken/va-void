<?php
declare(strict_types=1);

namespace App\Controller\Component;

use App\Error\Exception\ValidationException;
use Cake\Controller\Component;

/**
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization;
 */
class DeleteComponent extends Component
{
    protected array $components = ['Authorization.Authorization'];

    public function action(int|array $id): void
    {
        $controller = $this->getController();
        $model = $controller->fetchTable();

        $obj = $model->get($id);
        $this->Authorization->authorize($obj, 'delete');
        if (!$model->delete($obj)) {
            throw new ValidationException($obj);
        }

        $controller->setResponse($controller->getResponse()->withStatus(204));
        $controller->set('_serialize', null);
    }
}
