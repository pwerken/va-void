<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Http\Response;

class RootController extends AdminController
{
    /**
     * GET /admin
     * POST /admin
     */
    public function index(): ?Response
    {
        $this->getRequest()->allowMethod(['get', 'post']);

        $result = $this->Authentication->getResult();
        if ($this->getRequest()->is('post')) {
            if (!$result->isValid()) {
                $this->Flash->error('Invalid username or password');
            }
        }

        $redirect = $this->getRequest()->getQuery('redirect');
        if ($result->isValid()) {
            if (!empty($redirect)) {
                return $this->redirect($redirect);
            }
        }

        $this->set('socials', $this->SocialAuth->getProviders());
        $this->set('redirect', $redirect);
        $this->set('user', $result->getData());

        return null;
    }
}
