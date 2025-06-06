<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Entity\Player;

class AuthorizationController extends AdminController
{
    /**
     * GET /admin/authentication
     */
    public function index(): void
    {
        $user = $this->getRequest()->getAttribute('identity');

        $roles = Player::roleValues();
        if (!$user->hasAuth('Super')) {
            # hide 'Super' to prevent authorization envy
            array_pop($roles);
        }
        $this->set('roles', $roles);

        if (!$this->request->is('post')) {
            return;
        }

        $plin = $this->request->getData('plin');
        $role = $this->request->getData('role');

        $players = $this->fetchTable('Players');
        $player = $players->getMaybe($plin);
        if (is_null($player)) {
            $this->Flash->error("Player#$plin not found");

            return;
        }

        if ($player->role == $role) {
            $this->Flash->success("Player#$plin already as `$role` authorization");

            return;
        }

        $players->patchEntity($player, ['role' => $role]);
        if (!$player->isDirty('role')) {
            $this->Flash->error('Not authorized to change roles');

            return;
        }

        if (!$players->save($player)) {
            $errors = $player->getError('role');
            $this->Flash->error(reset($errors));
        } else {
            $this->Flash->success("Player#$plin has `$role` authorization");
        }
    }
}
