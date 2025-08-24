<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller as CakeController;

/**
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication;
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization;
 */
class Controller extends CakeController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization');

        $this->viewBuilder()->setClassName('Api');
    }
}
