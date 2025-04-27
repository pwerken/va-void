<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

class ViewComponent
    extends Component
{
    protected array $components = ['Authorization'];

    public function action(int|array $id, bool $checkAuthorize = true): void
    {
        $controller = $this->getController();
        $model = $controller->fetchTable();

        $obj = $model->getWithContain($id);
        if($checkAuthorize) {
            $this->Authorization->authorize($obj, 'view');
        }
        $this->Authorization->applyScope($obj, 'visible');

        $controller->set('_serialize', $obj);
    }
}
