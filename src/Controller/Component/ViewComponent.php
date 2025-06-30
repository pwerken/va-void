<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

/**
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization;
 */
class ViewComponent extends Component
{
    protected array $components = ['Authorization.Authorization'];

    public function action(int|array $id, bool $checkAuthorize = true): void
    {
        $controller = $this->getController();

        $obj = $controller->fetchTable()->get($id, 'withContain');
        if ($checkAuthorize) {
            $this->Authorization->authorize($obj, 'view');
        }
        $this->Authorization->applyScope($obj, 'visible');

        $controller->set('_serialize', $obj);
    }
}
