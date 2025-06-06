<?php
declare(strict_types=1);

namespace App\Controller\Admin;

class AuthenticationController extends AdminController
{
    /**
     * GET /admin/authentication
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

        $login->user_id = $player->id;

        if (!$profiles->save($login)) {
            $this->Flash->error("Failed to link SocialProfile#$social");
        } else {
            $this->Flash->success("SocialProfile#$social linked to Player#$plin");
        }
    }
}
