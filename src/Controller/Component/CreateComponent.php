<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

use App\Error\Exception\ValidationException;

class CreateComponent
    extends Component
{
    protected $components = ['Authorization', 'Read'];

    public function action()
    {
        $model = $this->getController()->loadModel();

        $obj = $model->newEmptyEntity();
        $this->Authorization->authorize($obj);
        $this->Authorization->applyScope($obj, 'accesible');

        $data = $this->getController()->getRequest()->getData();
        $obj = $model->patchEntity($obj, $data, ['associated' => []]);

        if (!$model->save($obj, ['checkExisting' => false]))
            throw new ValidationException($obj);

        #TODO
        var_dump($obj);
        die;
#        return $this->Read->action($id);
    }
}
