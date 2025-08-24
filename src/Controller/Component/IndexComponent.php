<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

/**
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization;
 */
class IndexComponent extends Component
{
    protected array $components = ['Authorization.Authorization'];

    public function action(): void
    {
        $controller = $this->getController();

        $query = $controller->fetchTable()->find('index');
        $this->Authorization->applyScope($query);

        $controller->set('_serialize', $query->all());
    }
}
