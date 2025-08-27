<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Enum\Authorization;
use App\Model\Enum\PlayerRole;
use Cake\Http\Response;

class AuthorizationController extends AdminController
{
    /**
     * GET /admin/authorization
     */
    public function index(): void
    {
        $this->getRequest()->allowMethod(['get']);

        $user = $this->getRequest()->getAttribute('identity');

        $roles = PlayerRole::cases();
        if (!$user->hasAuth(Authorization::Super)) {
            # hide 'Super' to prevent authorization envy
            array_pop($roles);
        }
        $this->set('roles', $roles);

        $players = $this->fetchTable('Players');

        $permissions = [];
        foreach ($roles as $role) {
            $query = $players
                    ->find()
                    ->where(['Players.role LIKE' => $role]);
            $permissions[$role->value] = [];
            foreach ($query->all() as $player) {
                $permissions[$role->value][$player->get('plin')] = $player->get('name');
            }
        }
        $this->set('permissions', $permissions);
    }

    /**
     * POST /admin/authorization/edit
     */
    public function edit(): Response
    {
        $this->getRequest()->allowMethod(['post']);

        $response = $this->redirect(['controller' => 'Authorization', 'action' => 'index']);

        $plin = $this->getRequest()->getData('plin');
        $role = $this->getRequest()->getData('role');

        $players = $this->fetchTable('Players');
        $player = $players->getMaybe($plin);
        if (is_null($player)) {
            $this->Flash->error("Player#$plin not found");

            return $response;
        }

        $who = $player->get('name');
        if ($player->get('role') == $role) {
            $this->Flash->success("$who (#$plin) already as `$role` authorization");

            return $response;
        }

        $this->Authorization->applyScope($player, 'accessible');
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
