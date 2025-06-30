<?php
declare(strict_types=1);

namespace App\Controller\Component;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Entity;
use Cake\Controller\Component;

/**
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization;
 */
class EditComponent extends Component
{
    protected array $components = ['Authorization.Authorization'];

    public function action(int|array $id): void
    {
        $controller = $this->getController();
        $model = $controller->fetchTable();

        $obj = $model->get($id);
        $this->Authorization->authorize($obj, 'edit');
        $this->Authorization->applyScope($obj, 'accessible');

        $data = $controller->getRequest()->getData();
        $obj = $model->patchEntity($obj, $data, ['associated' => []]);

        if (!$model->save($obj, ['checkExisting' => false])) {
            throw new validationexception($obj);
        }

        $controller->set('_serialize', Entity::refresh($obj));
    }
}
