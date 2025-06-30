<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\AddTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\ViewTrait;

/**
 * @property \App\Controller\Component\QueueLammyComponent $QueueLammy
 * @property \App\Model\Table\PowersTable $Powers;
 */
class PowersController extends Controller
{
    use AddTrait; // PUT /powers
    use ViewTrait; // GET /powers/{poin}
    use EditTrait; // PUT /powers/{poin}

    /**
     * GET /powers
     */
    public function index(): void
    {
        $query = $this->Powers->find()
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
     * POST /powers/{poin}/print
     */
    public function queue(int $poin): void
    {
        if ((string)$this->getRequest()->getBody() === 'all') {
            $power = $this->fetchTable()->get($poin, 'withContain');
            $table = $this->fetchTable('Lammies');

            $characters = $power->get('characters');
            foreach ($characters as $character) {
                $lammy = $table->newEmptyEntity();
                $lammy->set('target', $character->_joinData);
                $table->saveOrFail($lammy);
            }

            $this->set('_serialize', count($characters));

            return;
        }

        $this->loadComponent('QueueLammy');
        $this->QueueLammy->action($poin);
        $this->set('_serialize', 1);
    }
}
