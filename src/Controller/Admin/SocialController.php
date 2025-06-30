<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Http\Response;

class SocialController extends AdminController
{
    /**
     * GET /admin/social
     */
    public function index(): void
    {
        if (!$this->request->is('post')) {
            return;
        }

        $plin = $this->request->getData('plin');
        $social = $this->request->getData('social');

        if (empty($plin) || empty($social)) {
            return;
        }

        $profiles = $this->fetchTable('SocialProfiles');
        $login = $profiles->getMaybe($social);
        if (is_null($login)) {
            $this->Flash->error("SocialProfile#$social not found ?!");

            return;
        }

        // delete login attempt
        if (!is_null($this->request->getData('delete'))) {
            if (!$profiles->delete($login)) {
                $this->Flash->error("Failed to delete SocialProfile#$social");
            } else {
                $this->Flash->success("Deleted SocialProfile#$social");
            }

            return;
        }

        // link $plin to $social profile
        $players = $this->fetchTable('Players');
        $player = $players->getMaybe($plin);
        if (is_null($player)) {
            $this->Flash->error("Player#$plin not found");

            return;
        }

        $login->set('user_id', $player->get('id'));

        if (!$profiles->save($login)) {
            $this->Flash->error("Failed to link SocialProfile#$social");
        } else {
            $this->Flash->success("SocialProfile#$social linked to Player#$plin");
        }
    }

    /**
     * GET /admin/social/login/$providerName
     */
    public function login(string $providerName): Response
    {
        $redirect = $this->request->getQuery('redirect', '/admin');
        $redirect = $this->request->getData('redirect', $redirect);

        // save redirectUri for after the login callback
        $this->request->getSession()->write('redirect', $redirect);
        $this->request->getSession()->write('provider', $providerName);

        // redirect to login via social site
        $authUrl = $this->SocialAuth->authUrl($providerName);

        return $this->redirect($authUrl);
    }

    /**
     * GET /admin/social/callback
     */
    public function callback(): Response
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

        return $this->redirect(['controller' => 'Root']);
    }
}
