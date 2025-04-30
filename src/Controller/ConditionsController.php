<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\AddTrait;
use App\Controller\Traits\DeleteTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\ViewTrait;

/**
 * @property \App\Controller\Component\QueueLammyComponent $QueueLammy
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
     * POST /conditions/{coin}/print
     */
    public function queue(int $coin): void
    {
        if (array_key_exists('all', $this->getRequest()->getData())) {
            $condition = $this->fetchTable()->getWithContain($coin);
            $table = $this->fetchTable('Lammies');
            foreach ($condition->characters as $character) {
                $lammy = $table->newEmptyEntity();
                $lammy->set('target', $character);
                $table->saveOrFail($lammy);
            }

            return;
        }
        $this->loadComponent('QueueLammy');
        $this->QueueLammy->action($coin);
    }
}
