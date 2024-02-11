<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\Query;

use App\Model\Entity\AppEntity;

class IndexRelationComponent
    extends Component
{
    protected $components = ['Authorization'];

    public function action(AppEntity $parent, Query $query, string $authAction = 'view'): void
    {
        $this->Authorization->authorize($parent, $authAction);

        $modified = [$parent->modified];
        $objs = $query->all();
        foreach($objs as $obj) {
            $modified[] = $obj->modified;
        }

        $controller = $this->getController();
        $controller->checkModified($modified);
        $controller->set('parent', $parent);
        $controller->set('_serialize', $objs);
    }
}
