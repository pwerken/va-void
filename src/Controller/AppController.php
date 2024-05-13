<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Error\ErrorHandler;

class AppController
    extends Controller
{
    protected $parent = null;

    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization');

        $this->loadComponent('IndexRelation');
        $this->loadComponent('Add');
        $this->loadComponent('View');
        $this->loadComponent('Edit');
        $this->loadComponent('Delete');

        $this->loadComponent('QueueLammy');

        $this->viewBuilder()->setClassName('Api');
    }

    protected function doRawQuery($query)
    {
        // TODO FIX $this->request->getQuery('q') for searching

        $query->disableResultsCasting();
        return $query->execute()->fetchAll();
    }

    protected function doRawIndex($query, $class, $url, $id = 'id')
    {
        $content = [];
        foreach($this->doRawQuery($query) as $row) {
            $content[] =
                [ 'class' => $class
                , 'url'   => $url . $row[0]
                , $id     => (int)$row[0]
                , 'name'  => $row[1]
                ];
        }

        $this->set('_serialize',
            [ 'class' => 'List'
            , 'url' => rtrim($this->request->getPath(), '/')
            , 'list' => $content
            ]);
    }
}
