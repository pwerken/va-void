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

    // GET /
    public function root(): Response
    {
        $this->Authorization->skipAuthorization();

        return $this->redirect('/admin');
    }

    // OPTIONS /*
    public function cors(): Response
    {
        $this->Authorization->skipAuthorization();

        return new Response();
    }
}
