<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Entity;
use Cake\Controller\Controller as CakeController;
use Cake\ORM\Query\SelectQuery;

/**
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication;
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization;
 */
class Controller extends CakeController
{
    protected ?Entity $parent = null;

    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization');

        $this->viewBuilder()->setClassName('Api');
    }

    protected function doRawQuery(SelectQuery $query): mixed
    {
        // TODO FIX $this->request->getQuery('q') for searching

        $query->disableResultsCasting();

        return $query->execute()->fetchAll();
    }

    protected function doRawIndex(SelectQuery $query, string $class, string $url, string $id = 'id'): void
    {
        $content = [];
        foreach ($this->doRawQuery($query) as $row) {
            $content[] = [
                'class' => $class,
                'url' => $url . $row[0],
                $id => (int)$row[0],
                'name' => $row[1],
            ];
        }

        $this->set('_serialize', [
            'class' => 'List',
            'url' => rtrim($this->request->getPath(), '/'),
            'list' => $content,
        ]);
    }
}
