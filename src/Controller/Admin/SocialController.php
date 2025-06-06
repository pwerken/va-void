<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Http\Response;

class SocialController extends AdminController
{
    /**
     * GET /admin/social/$providerName
     */
    public function index(?string $providerName = null): Response
    {
        if (empty($providerName)) {
            return $this->redirect('/admin');
        }

        $this->SocialAuth->setCallbackUri([
            'controller' => 'Social',
            'action' => 'index',
            'callback',
        ]);

        if ($providerName == 'callback') {
            return $this->callback();
        }

        // save redirectUri for after the login callback
        $redirect = $this->request->getQuery('redirect', '/admin');
        $redirect = $this->request->getData('redirect', $redirect);
        $this->request->getSession()->write('redirect', $redirect);

        $this->request->getSession()->write('provider', $providerName);

        // redirect to login via social site
        $authUrl = $this->SocialAuth->authUrl($providerName);

        return $this->redirect($authUrl);
    }

    /**
     * GET /admin/social/callback
     */
    protected function callback(): Response
    {
        // callback from the social site login
        $providerName = $this->request->getSession()->consume('provider');
        $user = $this->SocialAuth->loginCallback($providerName);

        if ($user && $user->id) {
            $this->request->getSession()->write('Auth', $user);

            // redirect back to page from before login
            $redirect = $this->request->getSession()->consume('redirect');
            if (!empty($redirect)) {
                return $this->redirect($redirect);
            }
        } elseif ($user) {
            $this->Flash->error('Email is not linked to a plin.');
        } else {
            $this->Flash->error('Failed to login');
        }

        return $this->redirect('/admin');
    }
}
