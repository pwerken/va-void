<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Exception\NotFoundException;
use Migrations\Migrations;

use App\Command\Traits\Backup;
use App\Model\Entity\Player;

class AdminController
    extends Controller
{
    use Backup;

    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization');
        $this->loadComponent('SocialAuth');

        $this->Authentication->allowUnauthenticated(
            [ 'index'
            , 'logout'
            , 'social'
            , 'checks'
            , 'routes'
            ]);
        $this->set('user', $this->Authentication->getResult()->getData());
    }

    public function index()
    {
        $result = $this->Authentication->getResult();
        $redirect = $this->request->getQuery('redirect');
        if ($this->request->is('post'))
        {
            if (!$result->isValid()) {
                $this->Flash->error('Invalid username or password');
                return;
            }
            if (!empty($redirect)) {
                return $this->redirect($redirect);
            }
        }

        $this->set('socials', $this->SocialAuth->getProviders());
        $this->set('redirect', $redirect);
        $this->set('user', $result->getData());
    }

    public function logout()
    {
        $this->Authentication->logout();
        return $this->redirect(['controller' => 'Admin', 'action' => 'index']);
    }

    public function social($providerName = null)
    {
        if(empty($providerName)) {
            return $this->redirect(['controller' => 'Admin', 'action' => 'index']);
        }

        $this->SocialAuth->setCallbackUri(
            [ 'controller' => 'Admin'
            , 'action' => 'social'
            , 'callback'
            ]);

        if($providerName == 'callback') {
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

    protected function social_callback()
    {
        // callback from the social site login
        $providerName = $this->request->getSession()->consume('provider');
        $user = $this->SocialAuth->loginCallback($providerName);

        if($user and $user->id) {
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

    public function authentication()
    {
        if(!$this->request->is('post')) {
            return;
        }

        // modify legacy password

        $plin = $this->request->getData('plin');
        $pass = $this->request->getData('password');

        $this->loadModel('Players');
        $player = $this->Players->findById($plin)->first();

        if(is_null($player)) {
            $this->Flash->error("Player#$plin not found");
            return;
        }

        $this->Players->patchEntity($player, ['password' => $pass]);
        if (!$player->isDirty('password')) {
            $this->Flash->error("Not authorized to change passwords");
            return;
        }

        $this->Players->save($player);

        $errors = $player->getError('password');
        if(!empty($errors)) {
            $this->Flash->error(reset($errors));
        } else {
            $this->Flash->success("Player#$plin password set");
        }
    }

    public function authorization()
    {
        $user = $this->getRequest()->getAttribute('identity');

        $roles = Player::roleValues();
        if(!$user->hasAuth('Super')) {
            # hide 'Super' to prevent authorization envy
            array_pop($roles);
        }
        $this->set('roles', $roles);


        if(!$this->request->is('post')) {
            return;
        }

        $plin = $this->request->getData('plin');
        $role = $this->request->getData('role');

        $this->loadModel('Players');
        $player = $this->Players->findById($plin)->first();
        if(is_null($player)) {
            $this->Flash->error("Player#$plin not found");
            return;
        }

        $this->Players->patchEntity($player, ['role' => $role]);
        if (!$player->isDirty('role')) {
            $this->Flash->error("Not authorized to change roles");
            return;
        }

        if(!$this->Players->save($player)) {
            $errors = $player->getError('role');
            $this->Flash->error(reset($errors));
        } else {
            $this->Flash->success("Player#$plin has `$role` authorization");
        }
    }

    public function checks()
    {
        // handled by /templates/Admin/checks.php
    }

    public function routes()
    {
        // handled by /templates/Admin/routes.php
    }

    public function backups()
    {
        $this->set('backups', array_reverse($this->getBackupFiles()));
    }

    public function migrations()
    {
        $migrations = new Migrations();
        $this->set('migrations', array_reverse($migrations->status()));
    }

    public function history($e = NULL, $k1 = NULL, $k2 = NULL)
    {
        $table = $this->loadModel('History');
        if(!is_null($e)) {
            $plin = $this->request->getQuery('highlight');
            if(!empty($plin)) {
                $plin = (int)$plin;
            }
            $this->set('plin', $plin);

            $this->viewBuilder()->setTemplate('historyCompact');
            if(array_key_exists('verbose', $this->request->getQueryParams())) {
                $this->viewBuilder()->setTemplate('historyEntity');
            }

            $list = $table->getEntityHistory($e, $k1, $k2);
            if(empty($list)) {
                throw new NotFoundException();
            }
            $this->set('list', $list);
            return;
        }

        $plin = $this->request->getData('plin');
        if(!empty($plin)) {
            $plin = (int)$plin;
        }

        $since = $this->request->getData('since', '');
        $date = \DateTimeImmutable::createFromFormat("Y-m-d", $since);
        if(!$date) {
            $date = new \DateTime();
            $date->sub(new \DateInterval('P3M'));
        }
        $since = $date->format('Y-m-d');

        $what = $this->request->getData('what');

        $this->set('what', $what);
        $this->set('since', $since);
        $this->set('plin', $plin);

        $list = $table->getAllLastModified($plin, $since, $what);

        if($plin) {
            $all = $table->getAllModificationsBy($plin, $since, $what);
            $list = array_merge($list, $all);
            usort($list, array($table, 'compare'));
        }
        $this->set('list', $list);
    }

    public function printing($sides = NULL)
    {
        $lammies = $this->loadModel('Lammies');

        if(!is_null($sides)) {
            $queued = $lammies->find('Queued')->all();

            $this->set('double', ($sides == 'double'));
            $this->viewBuilder()->setClassName('Pdf');
            $this->set('lammies', $queued);
            $this->set('viewVar', 'lammies');

            $lammies->setStatuses($queued, 'Printed');
            return;
        }

        $user = $this->getRequest()->getAttribute('identity');

        if($this->request->is('post') && $user->hasAuth('Infobalie')) {
            $ids = $this->request->getData('delete');
            if(!empty($ids)) {
                $nr = $lammies->deleteAll(['id IN' => $ids]);
                $this->Flash->success("Removed $nr lammies from queue");
            }
        }

        $since = (new \DateTime())->sub(new \DateInterval('P3M'));
        $query = $lammies->find();
        $query
            ->select($lammies)
            ->select(['character_str' => $query->func()->concat(
                [ 'characters.player_id' => 'identifier', '/'
                , 'characters.chin' => 'identifier'])])
            ->leftJoin(['characters']
                     , [ 'Lammies.entity LIKE' => 'Character%'
                       , 'Lammies.key1 = characters.id'])
            ->where(['Lammies.modified >' => $since->format('Y-m-d')])
            ->orderDesc('Lammies.id')
            ->enableHydration(false);

        $this->set('printing', $query->all());
    }

    public function skills()
    {
        $skills = $this->loadModel('Skills');
        $this->set('skills', $skills->find('list')->all()->toArray());

        $characters = [];

        $user = $this->getRequest()->getAttribute('identity');
        if($this->request->is('post') && $user->hasAuth('Referee'))
        {
            $ids = $this->request->getData('skills');
            if(!is_array($ids))
                $ids = [$ids];

            $query = $this->loadModel('Characters')->find();
            $query->orderDesc('Characters.modified');
            $query->enableHydration(false);
            $query->innerJoinWith('CharactersSkills', function ($q) use ($ids) {
                    return $q->where(['CharactersSkills.skill_id IN' => $ids]);
                });

            foreach($query->all() as $c) {
                $id = $c['id'];
                $skill_id = $c['_matchingData']['CharactersSkills']['skill_id'];
                if(isset($characters[$id])) {
                    $characters[$id]['_matchingData'][$skill_id] = true;
                    continue;
                }
                $c['_matchingData'] = [$skill_id => true];
                $characters[$id] = $c;
            }
        }
        $this->set('characters', $characters);
    }
}
