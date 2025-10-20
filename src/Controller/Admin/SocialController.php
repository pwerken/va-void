<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Http\Response;

class SocialController extends AdminController
{
    /**
     * GET /admin/social
     * POST /admin/social
     */
    public function index(): ?Response
    {
        $this->getRequest()->allowMethod(['get', 'post']);

        if ($this->getRequest()->is('post')) {
            $this->index_post();

            return $this->redirect(['controller' => 'Social']);
        }

        $total = $this->fetchTable('SocialProfiles')
            ->find()
            ->all()
            ->count();

        $logins = $this->fetchTable('SocialProfiles')
            ->find()
            ->where(['user_id IS NULL', 'hidden' => false])
            ->orderByDesc('modified')
            ->all();

        $this->set('logins', $logins);
        $this->set('total', $total);

        return null;
    }

    protected function index_post(): ?Response
    {
        $social = $this->getRequest()->getData('social');
        if (empty($social)) {
            return null;
        }

        $profiles = $this->fetchTable('SocialProfiles');
        $login = $profiles->getMaybe($social);
        if (is_null($login)) {
            $this->Flash->error(sprintf('SocialProfile#%d not found', $social));

            return null;
        }

        // delete login attempt
        if (!is_null($this->getRequest()->getData('delete'))) {
            if (!$profiles->delete($login)) {
                $this->Flash->error(sprintf('SocialProfile#%d failed to delete', $social));
            } else {
                $this->Flash->success(sprintf('SocialProfile#%d removed', $social));
            }

            return null;
        }

        $plin = $this->getRequest()->getData('plin');
        if (empty($plin)) {
            $login->set('user_id', null);
            $login->set('hidden', true);
            if (!$profiles->save($login)) {
                $this->Flash->error(sprintf('SocialProfile#%d failed to disable', $social));
            } else {
                $this->Flash->success(sprintf('SocialProfile#%d disabled', $social));
            }

            return null;
        }

        // link $plin to $social profile
        $players = $this->fetchTable('Players');
        $player = $players->getMaybe($plin);
        if (is_null($player)) {
            $this->Flash->error(sprintf(
                'SocialProfile#%d failed to link, Player#%d not found',
                $social,
                $plin,
            ));

            return null;
        }

        $login->set('user_id', $player->get('id'));
        $login->set('hidden', false);
        if (!$profiles->save($login)) {
            $this->Flash->error(sprintf('SocialProfile#%d failed to link', $social));
        } else {
            $this->Flash->success(sprintf(
                'SocialProfile#%d linked to Player#%d',
                $social,
                $plin,
            ));
        }

        return $this->redirect(['controller' => 'Social']);
    }

    /**
     * GET /admin/social/all
     * POST /admin/social/all
     */
    public function all(): ?Response
    {
        $this->getRequest()->allowMethod(['get', 'post']);

        if ($this->getRequest()->is('post')) {
            $this->all_post();

            return $this->redirect(['controller' => 'Social', 'action' => 'all']);
        }

        $logins = $this->fetchTable('SocialProfiles')
            ->find()
            ->all();

        $this->set('logins', $logins);

        return null;
    }

    protected function all_post(): void
    {
        $social = $this->getRequest()->getData('social');
        if (empty($social)) {
            return;
        }

        $profiles = $this->fetchTable('SocialProfiles');
        $login = $profiles->getMaybe($social);
        if (is_null($login)) {
            $this->Flash->error(sprintf('SocialProfile#%d not found.', $social));

            return;
        }

        // enable login
        if (!is_null($this->getRequest()->getData('enable'))) {
            $login->set('hidden', false);
            if (!$profiles->save($login)) {
                $this->Flash->error(sprintf('SocialProfile#%d failed to enable', $social));
            } else {
                $this->Flash->success(sprintf('SocialProfile#%d enabled', $social));
            }

            return;
        }

        // unlink login from plin
        if (!is_null($this->getRequest()->getData('unlink'))) {
            $plin = $login->get('user_id');
            $login->set('user_id', null);
            if (!$profiles->save($login)) {
                $this->Flash->error(sprintf(
                    'SocialProfile#%d failed to unlink from Player#%d',
                    $social,
                    $plin,
                ));
            } else {
                $this->Flash->success(sprintf(
                    'SocialProfile#%d unlinked from Player#%d',
                    $social,
                    $plin,
                ));
            }

            return;
        }

        $this->Flash->error('Unknown action');
    }

    /**
     * GET /admin/social/login/$providerName
     * POST /admin/social/login/$providerName
     */
    public function login(string $providerName): Response
    {
        $this->getRequest()->allowMethod(['get', 'post']);

        $redirect = $this->getRequest()->getQuery('redirect', '/admin');
        $redirect = $this->getRequest()->getData('redirect', $redirect);

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
        $this->getRequest()->allowMethod(['get']);

        // callback from the social site login
        $providerName = $this->getRequest()->getSession()->consume('provider');
        if (is_null($providerName)) {
            return $this->redirect(['controller' => 'Root']);
        }

        $user = $this->SocialAuth->loginCallback($providerName);
        if ($user && $user->get('plin')) {
            $this->request->getSession()->write('Auth', $user);

            // redirect back to page from before login
            $redirect = $this->getRequest()->getSession()->consume('redirect');
            if (!empty($redirect)) {
                return $this->redirect($redirect);
            }
        } elseif ($user) {
            $this->Flash->error('Email is not linked to a plin');
        } else {
            $this->Flash->error('Failed to login');
        }

        return $this->redirect(['controller' => 'Root']);
    }
}
