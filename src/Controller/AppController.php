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

    public function checkModified($modified): void
    {
        if(empty($modified)) {
            return;
        }
        if(is_array($modified)) {
            $modified = max($modified);
        }

        $this->response = $this->response->withModified($modified);
        if($this->response->isNotModified($this->request)) {
            $this->response = $this->response->withNotModified($modified);
        }
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
        $modified = [];
        foreach($this->doRawQuery($query) as $row) {
            $content[] =
                [ 'class' => $class
                , 'url'   => $url . $row[0]
                , $id     => (int)$row[0]
                , 'name'  => $row[1]
                ];

            if(isset($row[2])) {
                $modified[] = $row[2];
            }
        }

        $this->checkModified($modified);
        $this->set('_serialize',
            [ 'class' => 'List'
            , 'url' => rtrim($this->request->getPath(), '/')
            , 'list' => $content
            ]);
    }
}
