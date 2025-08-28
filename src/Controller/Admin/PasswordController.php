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

        $plin = $this->getRequest()->getData('plin');
        $pass = $this->getRequest()->getData('password');

        $players = $this->fetchTable('Players');
        $player = $players->getMaybe($plin);
        if (is_null($player)) {
            $this->Flash->error(sprintf('Player#%d not found', $plin));

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
            $this->Flash->success(sprintf('Player#%d password removed', $plin));
        } else {
            $this->Flash->success(sprintf('Player#%d password set', $plin));
        }

        return $response;
    }
}
