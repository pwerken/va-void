<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Entity\Player;
use Cake\Http\Response;

class AuthorizationController extends AdminController
{
    /**
     * GET /admin/authorization
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
    }

    /**
     * POST /admin/authorization/edit
     */
    public function edit(): Response
    {
        $response = $this->redirect(['controller' => 'authorization', 'action' => 'index']);

        if (!$this->request->is('post')) {
            return $response;
        }

        $user = $this->getRequest()->getAttribute('identity');

        $roles = Player::roleValues();
        if (!$user->hasAuth('Super')) {
            # hide 'Super' to prevent authorization envy
            array_pop($roles);
        }
        $this->set('roles', $roles);

        $plin = $this->request->getData('plin');
        $role = $this->request->getData('role');

        $players = $this->fetchTable('Players');
        $player = $players->getMaybe($plin);
        if (is_null($player)) {
            $this->Flash->error("Player#$plin not found");

            return $response;
        }

        $who = $player->get('full_name');
        if ($player->get('role') == $role) {
            $this->Flash->success("$who (#$plin) already as `$role` authorization");

            return $response;
        }

        $players->patchEntity($player, ['role' => $role]);
        if (!$player->isDirty('role')) {
            $this->Flash->error('Not authorized to change roles');

            return $response;
        }

        if (!$players->save($player)) {
            $errors = $player->getError('role');
            $this->Flash->error(reset($errors));
        } else {
            $this->Flash->success("$who (#$plin) now has `$role` authorization");
        }

        return $response;
    }
}
