<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

use App\Error\Exception\ValidationException;

class DeleteComponent
    extends Component
{
    protected $components = ['Authorization'];

    public function action(int|array $id): void
    {
        $model = $this->getController()->loadModel();

        $obj = $model->get($id);
        $this->Authorization->authorize($obj, 'delete');

        if(!$model->delete($obj)) {
            throw new ValidationException($obj);
        }

        //TODO redirect? / return ...?
    }
}
