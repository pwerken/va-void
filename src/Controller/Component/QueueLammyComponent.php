<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

class QueueLammyComponent
	extends Component
{
    public function action($id): void
    {
        $controller = $this->getController();
        $model = $this->getController()->loadModel();

        $obj = $model->getWithContain($id);

		$table = $controller->loadModel('Lammies');
        $lammy = $table->newEmptyEntity();
        $lammy->set('target', $obj);
		$table->saveOrFail($lammy);

        $contoller->set('_serialize', 1);
    }
}
