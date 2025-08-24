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
class PowersController extends Controller
{
    use IndexTrait; // GET /powers
    use AddTrait; // PUT /powers
    use ViewTrait; // GET /powers/{poin}
    use EditTrait; // PUT /powers/{poin}
    use DeleteTrait; // DELETE /powers/{poin}

    /**
     * GET /powers/{poin}/print
     */
    public function pdf(int $poin): void
    {
        $all = !is_null($this->getRequest()->getQuery('all'));
        $double = !is_null($this->getRequest()->getQuery('double'));
        $lammies = $this->objectsForLammies($poin, $all);
        $this->Lammy->outputPdf($lammies, $double);
    }

    /**
     * POST /powers/{poin}/print
     */
    public function queue(int $poin): void
    {
        $all = (string)$this->getRequest()->getBody() === 'all';
        $lammies = $this->objectsForLammies($poin, $all);
        $this->Lammy->queueLammies($lammies);
    }

    /**
     * Helper for pdf() and queue() methods.
     */
    protected function objectsForLammies(int $poin, bool $all): array
    {
        $this->loadComponent('Lammy');

        $power = $this->fetchTable()->get($poin, 'withContain');
        if (!$all) {
            return [$this->Lammy->createLammy($power)];
        }

        $objs = [];
        foreach ($power->get('characters') as $character) {
            $c = $character->_joinData;
            $c->character = $character;
            $c->power = $power;
            $objs[] = $this->Lammy->createLammy($c);
        }

        return $objs;
    }
}
