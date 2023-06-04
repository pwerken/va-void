<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

class ViewComponent
    extends Component
{
    protected $components = ['Authorization'];

    public function action(int|array $id, bool $checkAuthorize = true): void
    {
        $model = $this->getController()->loadModel();

        $obj = $model->getWithContain($id);
        if($checkAuthorize) {
            $this->Authorization->authorize($obj, 'view');
        }

        $this->getController()->set('_serialize', $obj);
    }
}
