<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Http\Response;

class PasswordController extends AdminController
{
    /**
     * GET /admin/password
     */
    public function index(): void
    {
        $this->getRequest()->allowMethod(['get']);
    }

    /**
     * POST /admin/password/edit
     */
    public function edit(): Response
    {
        $this->getRequest()->allowMethod(['post']);
        $response = $this->redirect(['controller' => 'Password', 'action' => 'index']);

        if (!$this->request->is('post')) {
            return $response;
        }

        $plin = $this->request->getData('plin');
        $pass = $this->request->getData('password');

        $players = $this->fetchTable('Players');
        $player = $players->getMaybe($plin);
        if (is_null($player)) {
            $this->Flash->error("Player#$plin not found");

            return $response;
        }

        $this->Authorization->applyScope($player, 'accessible');
        $players->patchEntity($player, ['password' => $pass]);
        if (!$player->isDirty('password')) {
            $this->Flash->error('Not authorized to change password');

            return $response;
        }

        $players->save($player);
        $errors = $player->getError('password');
        if (!empty($errors)) {
            $this->Flash->error(reset($errors));
        } elseif (strlen($pass) == 0) {
            $this->Flash->success("Player#$plin password REMOVED");
        } else {
            $this->Flash->success("Player#$plin password set");
        }

        return $response;
    }
}
