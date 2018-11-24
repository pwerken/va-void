<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;

class QueueLammyComponent
	extends Component
{
	public function execute()
	{
		$controller = $this->_registry->getController();
		$controller->Crud->on('beforeRender', [$this, 'lammyBeforeRender']);
		$controller->Crud->execute();
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
