<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\AddTrait;
use App\Controller\Traits\DeleteTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\IndexTrait;
use App\Controller\Traits\ViewTrait;

/**
 * @property \App\Controller\Component\LammyComponent $Lammy
 */
class ConditionsController extends Controller
{
    use AddTrait; // PUT /conditions
    use DeleteTrait; // DELETE /conditions/{coin}
    use EditTrait; // PUT /conditions/{coin}
    use IndexTrait; // GET /conditions
    use ViewTrait; // GET /conditions/{coin}

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
