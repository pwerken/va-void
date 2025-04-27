<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

class QueueLammyComponent
	extends Component
{
    public function action(int|array $id): void
    {
        $controller = $this->getController();

        $obj = $controller->fetchTable()->getWithContain($id);

        $table = $controller->fetchTable('Lammies');
        $lammy = $table->newEmptyEntity();
        $lammy->set('target', $obj);
		$table->saveOrFail($lammy);

        $controller->set('_serialize', 1);
    }
}
