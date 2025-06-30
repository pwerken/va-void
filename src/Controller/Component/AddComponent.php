<?php
declare(strict_types=1);

namespace App\Controller\Component;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Entity;
use Cake\Controller\Component;

/**
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization;
 */
class AddComponent extends Component
{
    protected array $components = ['Authorization.Authorization'];

    public function action(bool $checkAuthorize = true): void
    {
        $controller = $this->getController();
        $model = $controller->fetchTable();

        $obj = $model->newEmptyEntity();
        if ($checkAuthorize) {
            $this->Authorization->authorize($obj, 'add');
        }
        $this->Authorization->applyScope($obj, 'accessible');

        $data = $controller->getRequest()->getData();
        $obj = $model->patchEntity($obj, $data, ['associated' => []]);

        if (!$model->save($obj, ['checkExisting' => false])) {
            throw new ValidationException($obj);
        }

        $obj = Entity::refresh($obj);

        $response = $controller->getResponse()
                        ->withLocation($obj->getUrl())
                        ->withStatus(201);
        $controller->setResponse($response);
        $controller->set('_serialize', $obj);
    }
}
