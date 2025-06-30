<?php
declare(strict_types=1);

namespace App\Controller\Component;

use App\Model\Entity\Entity;
use Cake\Controller\Component;
use Cake\ORM\Query\SelectQuery;

/**
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization;
 */
class IndexRelationComponent extends Component
{
    protected array $components = ['Authorization.Authorization'];

    public function action(Entity $parent, SelectQuery $query, string $action = 'view'): void
    {
        $controller = $this->getController();

        $this->Authorization->authorize($parent, $action);

        $controller->set('parent', $parent);
        $controller->set('_serialize', $query->all());
    }
}
