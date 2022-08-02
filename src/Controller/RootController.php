<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Http\Response;

class RootController
    extends Controller
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Authorization.Authorization');
    }

    public function root()
    {
        $this->Authorization->skipAuthorization();

        return $this->redirect('/admin');
    }

    public function cors()
    {
        $this->Authorization->skipAuthorization();

        return new Response();
    }
}
