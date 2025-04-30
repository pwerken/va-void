<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

class ViewComponent extends Component
{
    public function action(int|array $id, bool $checkAuthorize = true): void
    {
        $controller = $this->getController();
        $model = $controller->fetchTable();

        $obj = $model->getWithContain($id);
        if ($checkAuthorize) {
            $controller->Authorization->authorize($obj, 'view');
        }
        $controller->Authorization->applyScope($obj, 'visible');

        $controller->set('_serialize', $obj);
    }
}
