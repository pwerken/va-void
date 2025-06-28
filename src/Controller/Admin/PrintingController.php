<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use DateInterval;
use DateTime;

/**
 * @property \App\Controller\Component\SocialAuthComponent $SocialAuth;
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication;
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization;
 */
class PrintingController extends AdminController
{
    /**
     * GET /printing
     * POST /printing
     */
    public function index(): void
    {
        $lammies = $this->fetchTable('Lammies');
        $user = $this->getRequest()->getAttribute('identity');

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
     * GET /printing/double
     */
    public function double(): void
    {
        $lammies = $this->fetchTable('Lammies');
        $queued = $lammies->find('queued')->all();

        $this->viewBuilder()->setClassName('Pdf');

        $this->set('double', true);
        $this->set('lammies', $queued);
        $this->set('viewVar', 'lammies');

        $lammies->setStatuses($queued, 'Printed');
    }

    /**
     * GET /printing/single
     */
    public function single(): void
    {
        $this->double();
        $this->set('double', false);
    }
}
