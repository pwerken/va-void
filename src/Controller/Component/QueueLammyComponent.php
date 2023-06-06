<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

class QueueLammyComponent
	extends Component
{
    public function action(int|array $id): void
    {
        $model = $this->getController()->loadModel();
        $obj = $model->getWithContain($id);

		$table = $this->getController()->loadModel('Lammies');
        $lammy = $table->newEmptyEntity();
        $lammy->set('target', $obj);
		$table->saveOrFail($lammy);

        $this->getController()->set('_serialize', 1);
    }
}
