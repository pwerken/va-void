<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Enum\Authorization;
use App\Model\Enum\LammyStatus;
use Cake\Http\Response;
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
    public function index(): ?Response
    {
        $this->getRequest()->allowMethod(['get', 'post']);

        if ($this->getRequest()->is('post')) {
            return $this->index_post();
        }

        $since = (new DateTime())->sub(new DateInterval('P3M'));
        $query = $this->fetchTable('Lammies')->find('AdminListing')
            ->where(['Lammies.modified >' => $since->format('Y-m-d')])
            ->enableHydration(false);

        $this->set('printing', $query->all());

        return null;
    }

    protected function index_post(): Response
    {
        $user = $this->getRequest()->getAttribute('identity');
        if (!$user->hasAuth(Authorization::Infobalie)) {
            $this->Flash->error('Not authorized to remove lammies from queue');

            return $this->redirect(['controller' => 'Printing']);
        }

        $ids = $this->getRequest()->getData('delete');
        if (!empty($ids)) {
            $nr = $this->fetchTable()->deleteAll(['id IN' => $ids]);
            $this->Flash->success(sprintf('Removed %d lammies from queue', $nr));
        } else {
            $this->Flash->warning('No lammies removed from queue');
        }

        return $this->redirect(['controller' => 'Printing']);
    }

    /**
     * GET /printing/double
     */
    public function double(): void
    {
        $this->getRequest()->allowMethod(['get']);

        $lammies = $this->fetchTable('Lammies');
        $queued = $lammies->find('queued')->all();

        $this->viewBuilder()->setClassName('Pdf');

        $this->set('double', true);
        $this->set('lammies', $queued);
        $this->set('viewVar', 'lammies');

        $lammies->setStatuses($queued, LammyStatus::Printed);
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
