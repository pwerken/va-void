<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

use App\Error\Exception\ValidationException;

class EditComponent
    extends Component
{
    protected $components = ['Authorization', 'View'];

    public function action(int|array $id): void
    {
        $model = $this->getController()->loadModel();

        $obj = $model->get($id);
        $this->Authorization->authorize($obj, 'edit');
        $this->Authorization->applyScope($obj, 'accesible');

        $data = $this->getController()->getRequest()->getData();
        $obj = $model->patchEntity($obj, $data);

        if (!$model->save($obj)) {
            throw new validationexception($obj);
        }

        $this->View->action($id, false);
    }
}
