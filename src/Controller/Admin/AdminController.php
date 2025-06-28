<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Controller\Controller as CakeController;

/**
 * @property \App\Controller\Component\SocialAuthComponent $SocialAuth;
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication;
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization;
 * @property \Cake\Controller\Component\FlashComponent $Flash;
 */
abstract class AdminController extends CakeController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->defaultTable = null;

        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication', ['requireIdentity' => false]);
        $this->loadComponent('Authorization.Authorization');
        $this->loadComponent('SocialAuth');

        $this->set('user', $this->Authentication->getResult()?->getData());

        $this->SocialAuth->setCallbackUri([
            'controller' => 'Social',
            'action' => 'callback',
        ]);
    }
}
