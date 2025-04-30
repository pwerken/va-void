<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller as CakeController;
use Cake\Http\Response;

/**
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization;
 */
class RootController extends CakeController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Authorization.Authorization');
    }

    /**
     * GET /
     */
    public function index(): Response
    {
        $this->Authorization->skipAuthorization();

        return $this->redirect('/admin');
    }

    /**
     * OPTIONS /*
     */
    public function cors(): Response
    {
        $this->Authorization->skipAuthorization();

        return new Response();
    }
}
