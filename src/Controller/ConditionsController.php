<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\AddTrait;
use App\Controller\Traits\DeleteTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\ViewTrait;

/**
 * @property \App\Controller\Component\LammyComponent $Lammy
 * @property \App\Model\Table\ConditionsTable $Conditions;
 */
class ConditionsController extends Controller
{
    use AddTrait; // PUT /conditions
    use ViewTrait; // GET /conditions/{coin}
    use EditTrait; // PUT /conditions/{coin}
    use DeleteTrait; // DELETE /conditions/{coin}

    /**
     * GET /conditions
     */
    public function index(): void
    {
        $query = $this->Conditions->find()
                    ->select([], true)
                    ->select('Conditions.id')
                    ->select('Conditions.name')
                    ->select('Conditions.deprecated');
        $this->Authorization->applyScope($query);

        $content = [];
        foreach ($this->doRawQuery($query) as $row) {
            $content[] = [
                'class' => 'Condition',
                'url' => '/conditions/' . $row[0],
                'coin' => (int)$row[0],
                'name' => $row[1],
                'deprecated' => (bool)$row[2],
            ];
        }

        $this->set('_serialize', [
            'class' => 'List',
            'url' => rtrim($this->request->getPath(), '/'),
            'list' => $content,
        ]);
    }

    /**
     * GET /conditions/{coin}/print
     */
    public function pdf(int $coin): void
    {
        $all = !is_null($this->getRequest()->getQuery('all'));
        $double = !is_null($this->getRequest()->getQuery('double'));
        $lammies = $this->objectsForLammies($coin, $all);
        $this->Lammy->outputPdf($lammies, $double);
    }

    /**
     * POST /conditions/{coin}/print
     */
    public function queue(int $coin): void
    {
        $all = (string)$this->getRequest()->getBody() === 'all';
        $lammies = $this->objectsForLammies($coin, $all);
        $this->Lammy->queueLammies($lammies);
    }

    /**
     * Helper for pdf() and queue() methods.
     */
    protected function objectsForLammies(int $coin, bool $all): array
    {
        $this->loadComponent('Lammy');

        $condition = $this->fetchTable()->get($coin, 'withContain');
        if (!$all) {
            return [$this->Lammy->createLammy($condition)];
        }

        $objs = [];
        foreach ($condition->get('characters') as $character) {
            $c = $character->_joinData;
            $c->character = $character;
            $c->condition = $condition;
            $objs[] = $this->Lammy->createLammy($c);
        }

        return $objs;
    }
}
