<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Items Controller
 *
 * @property App\Model\Table\ItemsTable $Items
 */
class ItemsController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('index',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters' ]
			]);
		$this->Crud->mapAction('view',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters', 'Attributes' ]
			]);

		$this->Crud->mapAction('characterIndex',
			[ 'className' => 'Crud.Index'
			, 'contain' => [ 'Characters' ]
			]);
	}

	public function characterIndex($plin, $chin) {
		$this->loadModel('Characters');
		$parent = $this->Characters->plinChin($plin, $chin);
		$id = $parent->id;

		$this->Crud->on('beforePaginate',
			function(Event $event) use ($id) {
				$event->subject->query->where(['character_id' => $id]);
		});
		$this->set('parent', $parent);
		return $this->Crud->execute();
	}
}
