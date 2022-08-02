<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

class QueueLammyComponent
	extends Component
{
	public function execute()
	{
		$controller = $this->_registry->getController();
	}

	public function lammyBeforeRender(Event $event)
	{
		$controller = $this->_registry->getController();
		$table = $controller->loadModel('lammies');
		$entity = $event->getSubject()->entity;
		$table->save($table->newEntity()->set('target', $entity));
		$event->getSubject()->entity = 1;
	}
}
