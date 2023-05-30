<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

use App\Error\Exception\ValidationException;

class UpdateComponent
    extends Component
{
    protected $components = ['Authorization', 'Read'];

    public function action(int $id)
    {
        $model = $this->getController()->loadModel();

        $obj = $model->findById($id)->firstOrFail();
        $this->Authorization->authorize($obj);
        $this->Authorization->applyScope($obj, 'accesible');

        $data = $this->getController()->getRequest()->getData();
        $obj = $model->patchEntity($obj, $data);

        if (!$model->save($obj))
            throw new ValidationException($obj);

        return $this->Read->action($id);
    }
}
