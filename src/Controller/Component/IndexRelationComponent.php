<?php
declare(strict_types=1);

namespace App\Controller\Component;

use App\Model\Entity\Entity;
use Cake\Controller\Component;
use Cake\ORM\Query\SelectQuery;

class IndexRelationComponent extends Component
{
    public function action(Entity $parent, SelectQuery $query, string $authAction = 'view'): void
    {
        $controller = $this->getController();

        $controller->Authorization->authorize($parent, $authAction);

        $controller->set('parent', $parent);
        $controller->set('_serialize', $query->all());
    }
}
