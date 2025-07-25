<?php
declare(strict_types=1);

namespace App\Controller\Admin;

class PasswordController extends AdminController
{
    /**
     * GET /admin/password
     * POST /admin/password
     */
    public function index(): void
    {
        if (!$this->request->is('post')) {
            return;
        }
        $plin = $this->request->getData('plin');
        $pass = $this->request->getData('password');

        $players = $this->fetchTable('Players');
        $player = $players->getMaybe($plin);
        if (is_null($player)) {
            $this->Flash->error("Player#$plin not found");

            return;
        }

        $players->patchEntity($player, ['password' => $pass]);
        if (!$player->isDirty('password')) {
            $this->Flash->error('Not authorized to change passwords');

            return;
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
    }
}
