<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\AddTrait;
use App\Controller\Traits\DeleteTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\ViewTrait;

/**
 * @property \App\Controller\Component\LammyComponent $Lammy
 */
class PowersController extends Controller
{
    use AddTrait; // PUT /powers
    use DeleteTrait; // DELETE /powers/{poin}
    use EditTrait; // PUT /powers/{poin}
    use ViewTrait; // GET /powers/{poin}

    /**
     * GET /powers
     */
    public function index(): void
    {
        $query = $this->fetchTable()->find()
                    ->select([], true)
                    ->select('Powers.id')
                    ->select('Powers.name')
                    ->select('Powers.deprecated');
        $this->Authorization->applyScope($query);

        $content = [];
        foreach ($this->doRawQuery($query) as $row) {
            $content[] = [
                'class' => 'Power',
                'url' => '/powers/' . $row[0],
                'poin' => (int)$row[0],
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
