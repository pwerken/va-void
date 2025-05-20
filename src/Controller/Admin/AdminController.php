<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Command\Traits\BackupTrait;
use App\Model\Entity\Player;
use Cake\Controller\Controller as CakeController;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Migrations\Migrations;

/**
 * @property \App\Controller\Component\SocialAuthComponent $SocialAuth;
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication;
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization;
 */
class AdminController extends CakeController
{
    use BackupTrait;

    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization');
        $this->loadComponent('SocialAuth');

        $this->Authentication->allowUnauthenticated([
            'index',
            'logout',
            'social',
            'checks',
            'routes',
        ]);
        $this->set('user', $this->Authentication->getResult()->getData());
    }

    /**
     * GET /admin
     * POST /admin
     */
    public function index(): ?Response
    {
        $result = $this->Authentication->getResult();
        if ($this->request->is('post')) {
            if (!$result->isValid()) {
                $this->Flash->error('Invalid username or password');
            }
        }

        $redirect = $this->request->getQuery('redirect');
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

    /**
     * GET /admin/logout
     */
    public function logout(): ?Response
    {
        $this->Authentication->logout();

        return $this->redirect(['controller' => 'Admin', 'action' => 'index']);
    }

    /**
     * GET /admin/social/$providerName
     */
    public function social(?string $providerName = null): ?Response
    {
        if (empty($providerName)) {
            return $this->redirect(['controller' => 'Admin', 'action' => 'index']);
        }

        $this->SocialAuth->setCallbackUri([
            'controller' => 'Admin',
            'action' => 'social',
            'callback',
        ]);

        if ($providerName == 'callback') {
            return $this->social_callback();
        }

        $redirect = $this->request->getQuery('redirect', '/admin');
        $redirect = $this->request->getData('redirect', $redirect);
        // save redirectUri for after the login callback
        $this->request->getSession()->write('redirect', $redirect);

        // redirect to login via social site
        $this->request->getSession()->write('provider', $providerName);
        $authUrl = $this->SocialAuth->authUrl($providerName);

        return $this->redirect($authUrl);
    }

    /**
     * GET /admin/social/callback
     */
    protected function social_callback(): ?Response
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

        return $this->redirect(['controller' => 'Admin', 'action' => 'index']);
    }

    /**
     * GET /admin/authentication
     */
    public function authentication(): void
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
        $login = $profiles->findById($social)->first();
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
        $player = $players->findById($plin)->first();
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

    /**
     * GET /admin/password
     */
    public function password()
    {
        if (!$this->request->is('post')) {
            return;
        }
        $plin = $this->request->getData('plin');
        $pass = $this->request->getData('password');

        $players = $this->fetchTable('Players');
        $player = $players->findById($plin)->first();
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

    /**
     * GET /admin/authorization
     */
    public function authorization(): void
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
        $player = $players->findById($plin)->first();
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

    /**
     * GET /admin/checks
     */
    public function checks()
    {
        // handled by /templates/Admin/checks.php
    }

    /**
     * GET /admin/routes
     */
    public function routes()
    {
        // handled by /templates/Admin/routes.php
    }

    /**
     * GET /admin/backups
     */
    public function backups()
    {
        $this->set('backups', array_reverse($this->getBackupFiles()));
    }

    /**
     * GET /admin/migrations
     */
    public function migrations()
    {
        $migrations = new Migrations();
        $this->set('migrations', array_reverse($migrations->status()));
    }

    /**
     * GET /admin/history/...
     */
    public function history(?string $e = null, string|int|null $k1 = null, string|int|null $k2 = null): void
    {
        $history = $this->fetchTable('History');
        if (!is_null($e)) {
            $plin = $this->request->getQuery('highlight');
            $plin = empty($plin) ? null : (int)$plin;

            $this->viewBuilder()->setTemplate('historyCompact');
            if (array_key_exists('verbose', $this->request->getQueryParams())) {
                $this->viewBuilder()->setTemplate('historyEntity');
            }

            $list = $history->getEntityHistory($e, $k1, $k2);
            if (empty($list)) {
                throw new NotFoundException();
            }

            $this->set('plin', $plin);
            $this->set('list', $list);

            return;
        }

        $plin = $this->request->getQuery('plin');
        $plin = empty($plin) ? null : (int)$plin;

        $since = $this->request->getQuery('since', '');
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $since);
        if (!$date) {
            $date = new DateTime();
            $date->sub(new DateInterval('P3M'));
        }
        $since = $date->format('Y-m-d');

        $what = $this->request->getQuery('what');

        $this->set('what', $what);
        $this->set('since', $since);
        $this->set('plin', $plin);

        $list = $history->getAllLastModified($plin, $since, $what);

        if ($plin) {
            $all = $history->getAllModificationsBy($plin, $since, $what);
            $list = array_merge($list, $all);
            usort($list, [$history, 'compare']);
        }
        $this->set('list', $list);
    }

    /**
     * GET /printing
     * GET /printing/single
     * GET /printing/double
     * POST /printing
     */
    public function printing(?string $sides = null): void
    {
        $lammies = $this->fetchTable('Lammies');
        $user = $this->getRequest()->getAttribute('identity');

        if (!is_null($sides) && $user->hasAuth('Infobalie')) {
            $queued = $lammies->find('Queued')->all();

            $this->set('double', ($sides == 'double'));
            $this->viewBuilder()->setClassName('Pdf');
            $this->set('lammies', $queued);
            $this->set('viewVar', 'lammies');

            $lammies->setStatuses($queued, 'Printed');

            return;
        }

        if ($this->request->is('post') && $user->hasAuth('Infobalie')) {
            $ids = $this->request->getData('delete');
            if (!empty($ids)) {
                $nr = $lammies->deleteAll(['id IN' => $ids]);
                $this->Flash->success("Removed $nr lammies from queue");
            }
        }

        $since = (new DateTime())->sub(new DateInterval('P3M'));
        $query = $lammies->find();
        $query
            ->select($lammies)
            ->select(['character_str' => $query->func()->concat([
                'characters.player_id' => 'identifier',
                '/',
                'characters.chin' => 'identifier',
            ])])
            ->leftJoin(['characters'], [
                'Lammies.entity LIKE' => 'Character%',
                'Lammies.key1 = characters.id',
            ])
            ->where(['Lammies.modified >' => $since->format('Y-m-d')])
            ->orderDesc('Lammies.id')
            ->enableHydration(false);

        $this->set('printing', $query->all());
    }

    /**
     * GET /skills
     * POST /skills
     */
    public function skills()
    {
        $skills = $this->fetchTable('Skills');
        $this->set('skills', $skills->find('list')->all()->toArray());

        $characters = [];

        $user = $this->getRequest()->getAttribute('identity');
        if ($this->request->is('post') && $user->hasAuth('Referee')) {
            $ids = $this->request->getData('skills');
            if (!is_array($ids)) {
                $ids = [$ids];
            }

            $query = $this->fetchTable('Characters')->find();
            $query->orderDesc('Characters.modified');
            $query->enableHydration(false);
            $query->innerJoinWith('CharactersSkills', function ($q) use ($ids) {
                return $q->where(['CharactersSkills.skill_id IN' => $ids]);
            });

            foreach ($query->all() as $c) {
                $id = $c['id'];
                $skill_id = $c['_matchingData']['CharactersSkills']['skill_id'];
                $times = $c['_matchingData']['CharactersSkills']['times'];
                if (isset($characters[$id])) {
                    $characters[$id]['_matchingData'][$skill_id] = $times;
                    continue;
                }
                $c['_matchingData'] = [$skill_id => $times];
                $characters[$id] = $c;
            }
        }
        $this->set('characters', $characters);
    }
}
