<?php
declare(strict_types=1);

namespace App\Controller\Component;

use App\Model\Entity\Lammy;
use Cake\Controller\Component;
use Cake\Datasource\EntityInterface;

class LammyComponent extends Component
{
    public function actionPdf(int|array $id): void
    {
        $lammy = $this->getLammy($id);
        $this->outputPdf([$lammy]);
    }

    public function actionQueue(int|array $id): void
    {
        $lammy = $this->getLammy($id);
        $this->queueLammies([$lammy]);
    }

    public function createLammy(EntityInterface $obj): Lammy
    {
        $lammy = $this->getController()
                    ->fetchTable('Lammies')
                    ->newEmptyEntity();
        $lammy->set('target', $obj);

        return $lammy;
    }

    public function outputPdf(array $lammies): void
    {
        $controller = $this->getController();
        $controller->viewBuilder()->setClassName('Pdf');
        $controller->set('viewVar', 'lammies');
        $controller->set('lammies', $lammies);
        $controller->set('double', false);
    }

    public function queueLammies(array $lammies): void
    {
        $controller = $this->getController();
        $table = $controller->fetchTable('Lammies');

        $count = 0;
        foreach ($lammies as $lammy) {
            $table->saveOrFail($lammy);
            $count++;
        }

        $controller->set('_serialize', $count);
    }

    protected function getLammy(int|array $id): Lammy
    {
        $obj = $this->getController()->fetchTable()->get($id, 'withContain');

        return $this->createLammy($obj);
    }
}
